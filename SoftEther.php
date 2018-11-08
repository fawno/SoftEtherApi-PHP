<?php

namespace SoftEtherApi
{
    require('Infrastructure\SHA0.php');
    require('Containers\SoftEtherHashPair.php');
    require('Containers\SoftEtherNetwork.php');
    require('Containers\SoftEtherProtocol.php');

    require('Api\SoftEtherServer.php');
    require('Api\SoftEtherHub.php');

    require('SoftEtherModel\ConnectResult.php');
    require('SoftEtherModel\AuthResult.php');

    use SoftEtherApi\Api\SoftEtherHub;
    use SoftEtherApi\Api\SoftEtherServer;
    use SoftEtherApi\Containers\SoftEtherError;
    use SoftEtherApi\Containers\SoftEtherProtocol;
    use SoftEtherApi\Containers\SoftEtherValueType;
    use SoftEtherApi\Infrastructure\SHA0;
    use SoftEtherApi\Containers\SoftEtherHashPair;
    use SoftEtherApi\SoftEtherNetwork;
    use SoftEtherApi\SoftEtherModel\ConnectResult;
    use SoftEtherApi\SoftEtherModel\AuthResult;

    class SoftEther
    {
        private $socket;

        public $RandomFromServer;

        public $ServerApi;
        public $HubApi;

        public function __construct($host, $port)
        {
            $contextOptions = stream_context_create([
                'ssl' => [
                    'verify_peer' => false, // You could skip all of the trouble by changing this to false, but it's WAY uncool for security reasons.
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    //'cafile' => '/etc/ssl/certs/cacert.pem',
                    //'CN_match' => 'example.com', // Change this to your certificates Common Name (or just comment this line out if not needed)
                ]
            ]);

            $this->socket = stream_socket_client("ssl://${host}:${port}", $errno, $errstr, 20, STREAM_CLIENT_CONNECT, $contextOptions);
            $this->RandomFromServer = null;

            if (!$this->socket)
            {
                echo "$errstr ($errno)";
            }
            $this->ServerApi = new SoftEtherServer($this);
            $this->HubApi = new SoftEtherHub($this);
        }

        public function close()
        {
            fclose($this->socket);
        }

        public function Connect()
        {
            SoftEtherNetwork\SendHttpRequest($this->socket, 'POST', '/vpnsvc/connect.cgi', 'VPNCONNECT',
                SoftEtherNetwork\GetDefaultHeaders());

            $connectResponse = SoftEtherNetwork\GetHttpResponse($this->socket);
            if ($connectResponse->code != 200)
                return new ConnectResult(SoftEtherError::ConnectFailed);

            $connectDict = SoftEtherProtocol::Deserialize($connectResponse->body);
            $connectResult = ConnectResult::Deserialize($connectDict);

            if ($connectResult->Valid())
                $this->RandomFromServer = $connectResult->random[0];

            return $connectResult;
        }

        public function Authenticate($password, $hubName = null)
        {
            $passwordHash = self::CreatePasswordHash($password);
            return $this->AuthenticateHash($passwordHash, $hubName);
        }

        public function AuthenticateHash($passwordHash, $hubName = null)
        {
            if ($this->RandomFromServer == null)
                return new AuthResult(SoftEtherError::ConnectFailed);

            $authPayload =
                [
                    'method' => ['type' => SoftEtherValueType::String, 'value' => ['admin']],
                    'client_str' => ['type' => SoftEtherValueType::String, 'value' => ['SoftEtherNet']],
                    'client_ver' => ['type' => SoftEtherValueType::Int, 'value' => [1]],
                    'client_build' => ['type' => SoftEtherValueType::Int, 'value' => [0]]
                ];

            if ($hubName !== null && $hubName !== '')
                $authPayload['hubname'] = ['type' => SoftEtherValueType::String, 'value' => [$hubName]];

            $securePassword = self::CreateSaltedHash($passwordHash, $this->RandomFromServer);
            $authPayload['secure_password'] = ['type' => SoftEtherValueType::Raw, 'value' => [$securePassword]];

            $serializedAuthPayload = SoftEtherProtocol::Serialize($authPayload);
            SoftEtherNetwork\SendHttpRequest($this->socket, 'POST', '/vpnsvc/vpn.cgi', $serializedAuthPayload, SoftEtherNetwork\GetDefaultHeaders());

            $authResponse = SoftEtherNetwork\GetHttpResponse($this->socket);

            if ($authResponse->code != 200)
                return new AuthResult(SoftEtherError::AuthFailed);

            $authDict = SoftEtherProtocol::Deserialize($authResponse->body);
            return AuthResult::Deserialize($authDict);
        }

        public function CallMethod($functionName, $payload = null)
        {
            if ($payload == null)
                $payload = [];

            //payload.RemoveNullParameters();
            $payload['function_name'] = ['type' => SoftEtherValueType::String, 'value' => [$functionName]];

            $serializedPayload = SoftEtherProtocol::Serialize($payload);
            $serializedLength = SoftEtherProtocol::SerializeInt(strlen($serializedPayload));

            fwrite($this->socket, $serializedLength);
            fwrite($this->socket, $serializedPayload);

            $dataLength = fread($this->socket, 4);

            if (strlen($dataLength) != 4)
                throw new Exception('Failed to read dataLength');

            $dataLengthAsInt = SoftEtherProtocol::DeserializeInt($dataLength);
            $responseBuffer = '';

            for ($i = 0; $i < 10; $i++) //retrie 10 times to read all data
            {
                $responseBuffer .= fread($this->socket, $dataLengthAsInt - strlen($responseBuffer));
                if (strlen($responseBuffer) == $dataLengthAsInt)
                    break;
                usleep(50000);
            }

            if (strlen($responseBuffer) != $dataLengthAsInt)
                throw new Exception('read less than dataLength');

            $response = SoftEtherProtocol::Deserialize($responseBuffer);

            return $response;
        }

        public static function CreateUserHashAndNtLm($name, $password)
        {
            $hashedPw = self::CreateUserPasswordHash($name, $password);
            $ntlmHash = self::CreateNtlmHash($password);
            return new SoftEtherHashPair($hashedPw, $ntlmHash);
        }

        public static function CreateUserPasswordHash($username, $password)
        {
            $hashCreator = new SHA0();
            $hashCreator->Update(unpack('C*', $password));
            $hashedPw = $hashCreator->Update(unpack('C*', strtoupper($username)))->Digest();
            return $hashedPw;
        }

        public static function CreateNtlmHash($password)
        {
            $hashPrepare =iconv('UTF-8','UTF-16LE',$password);
            $ntlmHash = hash('md4', $hashPrepare, true);
            return unpack('C*', $ntlmHash);
        }

        public function CreateHashAnSecure($password)
        {
            $hashedPw = self::CreatePasswordHash($password);
            $saltedPw = self::CreateSaltedHash($hashedPw, $this->RandomFromServer);

            return new SoftEtherHashPair($hashedPw, $saltedPw);
        }

        public static function CreatePasswordHash($password)
        {
            $hashCreator = new SHA0();
            $hashedPw = $hashCreator->Update(unpack('C*', $password))->Digest();

            return $hashedPw;
        }

        public static function CreateSaltedHash($passwordHash, $salt)
        {
            $hashCreator = new SHA0();
            $hashCreator->Update($passwordHash);
            $saltedPw = $hashCreator->Update($salt)->Digest();

            return $saltedPw;
        }
    }
}

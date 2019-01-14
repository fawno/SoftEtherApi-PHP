<?php


namespace SoftEtherApi
{
    spl_autoload_register(function ($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    });

    use Exception;
    use SoftEtherApi\Api;
    use SoftEtherApi\Containers;
    use SoftEtherApi\Infrastructure;
    use SoftEtherApi\SoftEtherModel;

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
            $this->ServerApi = new Api\SoftEtherServer($this);
            $this->HubApi = new Api\SoftEtherHub($this);
        }

        public function close()
        {
            fclose($this->socket);
        }

        public function Connect()
        {
            Containers\SoftEtherNetwork::SendHttpRequest($this->socket, 'POST', '/vpnsvc/connect.cgi', 'VPNCONNECT',
                Containers\SoftEtherNetwork::GetDefaultHeaders());

            $connectResponse = Containers\SoftEtherNetwork::GetHttpResponse($this->socket);
            if ($connectResponse->code != 200)
                return new SoftEtherModel\ConnectResult(Containers\SoftEtherError::ConnectFailed);

            $connectDict = Containers\SoftEtherProtocol::Deserialize($connectResponse->body);
            $connectResult = SoftEtherModel\ConnectResult::Deserialize($connectDict);

            if ($connectResult->Valid())
                $this->RandomFromServer = $connectResult->random;

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
                return new SoftEtherModel\AuthResult(Containers\SoftEtherError::ConnectFailed);

            $authPayload =
                [
                    'method' => ['type' => Containers\SoftEtherValueType::String, 'value' => ['admin']],
                    'client_str' => ['type' => Containers\SoftEtherValueType::String, 'value' => ['SoftEtherNet']],
                    'client_ver' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [1]],
                    'client_build' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [0]]
                ];

            if ($hubName !== null && $hubName !== '')
                $authPayload['hubname'] = ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]];

            $securePassword = self::CreateSaltedHash($passwordHash, $this->RandomFromServer);
            $authPayload['secure_password'] = ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$securePassword]];

            $serializedAuthPayload = Containers\SoftEtherProtocol::Serialize($authPayload);
            Containers\SoftEtherNetwork::SendHttpRequest($this->socket, 'POST', '/vpnsvc/vpn.cgi', $serializedAuthPayload, Containers\SoftEtherNetwork::GetDefaultHeaders());

            $authResponse = Containers\SoftEtherNetwork::GetHttpResponse($this->socket);

            if ($authResponse->code != 200)
                return new SoftEtherModel\AuthResult(Containers\SoftEtherError::AuthFailed);

            $authDict = Containers\SoftEtherProtocol::Deserialize($authResponse->body);
            return SoftEtherModel\AuthResult::Deserialize($authDict);
        }

        public function CallMethod($functionName, $payload = null)
        {
            if ($payload == null)
                $payload = [];

            //payload.RemoveNullParameters();
            $payload['function_name'] = ['type' => Containers\SoftEtherValueType::String, 'value' => [$functionName]];

            $serializedPayload = Containers\SoftEtherProtocol::Serialize($payload);
            $serializedLength = Containers\SoftEtherProtocol::SerializeInt(strlen($serializedPayload));

            fwrite($this->socket, $serializedLength);
            fwrite($this->socket, $serializedPayload);

            $dataLength = fread($this->socket, 4);

            if (strlen($dataLength) != 4)
                throw new Exception('Failed to read dataLength');

            $dataLengthAsInt = Containers\SoftEtherProtocol::DeserializeInt($dataLength);
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

            $response = Containers\SoftEtherProtocol::Deserialize($responseBuffer);

            return $response;
        }

        public static function CreateUserHashAndNtLm($name, $password)
        {
            $hashedPw = self::CreateUserPasswordHash($name, $password);
            $ntlmHash = self::CreateNtlmHash($password);
            return new Containers\SoftEtherHashPair($hashedPw, $ntlmHash);
        }

        public static function CreateUserPasswordHash($username, $password)
        {
            $hashCreator = new Infrastructure\SHA0();
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

            return new Containers\SoftEtherHashPair($hashedPw, $saltedPw);
        }

        public static function CreatePasswordHash($password)
        {
            $hashCreator = new Infrastructure\SHA0();
            $hashedPw = $hashCreator->Update(unpack('C*', $password))->Digest();

            return $hashedPw;
        }

        public static function CreateSaltedHash($passwordHash, $salt)
        {
            $hashCreator = new Infrastructure\SHA0();
            $hashCreator->Update($passwordHash);
            $saltedPw = $hashCreator->Update($salt)->Digest();

            return $saltedPw;
        }
    }
}

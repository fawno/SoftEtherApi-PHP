<?php
	declare(strict_types=1);

	namespace SoftEtherApi;

	use Exception;
	use SoftEtherApi\Api\SoftEtherHub;
	use SoftEtherApi\Api\SoftEtherServer;
	use SoftEtherApi\Containers\SoftEtherError;
	use SoftEtherApi\Containers\SoftEtherHashPair;
	use SoftEtherApi\Containers\SoftEtherNetwork;
	use SoftEtherApi\Containers\SoftEtherProtocol;
	use SoftEtherApi\Containers\SoftEtherValueType;
	use SoftEtherApi\Infrastructure\SHA0;
	use SoftEtherApi\SoftEtherModel\AuthResult;
	use SoftEtherApi\SoftEtherModel\ConnectResult;

	class SoftEther {
		private $socket;

		public $RandomFromServer;

		public $ServerApi;
		public $HubApi;

		public function __construct (string $host, int $port, $stream_context = null) {
			$stream_context = $stream_context ?? stream_context_create([
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true,
				]
			]);

			$this->socket = @stream_socket_client("ssl://${host}:${port}", $errno, $errstr, 20, STREAM_CLIENT_CONNECT, $stream_context);
			$this->RandomFromServer = null;

			if (!$this->socket) {
				throw new SoftEtherException($errstr, $errno);
			}

			$this->ServerApi = new SoftEtherServer($this);
			$this->HubApi = new SoftEtherHub($this);
		}

		public function close () : void {
			fclose($this->socket);
		}

		public function Connect () {
			SoftEtherNetwork::SendHttpRequest($this->socket, 'POST', '/vpnsvc/connect.cgi', 'VPNCONNECT', SoftEtherNetwork::GetDefaultHeaders());

			$connectResponse = SoftEtherNetwork::GetHttpResponse($this->socket);
			if ($connectResponse->code != 200) {
				return new ConnectResult(SoftEtherError::ConnectFailed);
			}

			$connectDict = SoftEtherProtocol::Deserialize($connectResponse->body);
			$connectResult = ConnectResult::Deserialize($connectDict);

			if ($connectResult->Valid()) {
				$this->RandomFromServer = $connectResult->random;
			}

			return $connectResult;
		}

		public function Authenticate (string $password, ?string $hubName = null) {
			$passwordHash = self::CreatePasswordHash($password);
			return $this->AuthenticateHash($passwordHash, $hubName);
		}

		public function AuthenticateHash (array $passwordHash, ?string $hubName = null) {
			if ($this->RandomFromServer == null) {
				return new AuthResult(SoftEtherError::ConnectFailed);
			}

			$authPayload = [
				'method' => ['type' => SoftEtherValueType::String, 'value' => ['admin']],
				'client_str' => ['type' => SoftEtherValueType::String, 'value' => ['SoftEtherNet']],
				'client_ver' => ['type' => SoftEtherValueType::Int, 'value' => [1]],
				'client_build' => ['type' => SoftEtherValueType::Int, 'value' => [0]],
			];

			if ($hubName !== null && $hubName !== '') {
				$authPayload['hubname'] = ['type' => SoftEtherValueType::String, 'value' => [$hubName]];
			}

			$securePassword = self::CreateSaltedHash($passwordHash, $this->RandomFromServer);
			$authPayload['secure_password'] = ['type' => SoftEtherValueType::Raw, 'value' => [$securePassword]];

			$serializedAuthPayload = SoftEtherProtocol::Serialize($authPayload);
			SoftEtherNetwork::SendHttpRequest($this->socket, 'POST', '/vpnsvc/vpn.cgi', $serializedAuthPayload, SoftEtherNetwork::GetDefaultHeaders());

			$authResponse = SoftEtherNetwork::GetHttpResponse($this->socket);

			if ($authResponse->code != 200) {
				return new AuthResult(SoftEtherError::AuthFailed);
			}

			$authDict = SoftEtherProtocol::Deserialize($authResponse->body);
			return AuthResult::Deserialize($authDict);
		}

		public function CallMethod (string $functionName, array $payload = []) {
			//payload.RemoveNullParameters();
			$payload['function_name'] = ['type' => SoftEtherValueType::String, 'value' => [$functionName]];

			$serializedPayload = SoftEtherProtocol::Serialize($payload);
			$serializedLength = SoftEtherProtocol::SerializeInt(strlen($serializedPayload));

			fwrite($this->socket, $serializedLength);
			fwrite($this->socket, $serializedPayload);

			$dataLength = fread($this->socket, 4);

			if (strlen($dataLength) != 4) {
				throw new Exception('Failed to read dataLength');
			}

			$dataLengthAsInt = SoftEtherProtocol::DeserializeInt($dataLength);
			$responseBuffer = '';

			$tries = 0;
			do {
				$responseBuffer .= fread($this->socket, $dataLengthAsInt - strlen($responseBuffer));
				usleep(50000);
			} while (strlen($responseBuffer) < $dataLengthAsInt and 10 > $tries++);

			if (strlen($responseBuffer) != $dataLengthAsInt) {
				throw new Exception('read less than dataLength');
			}

			$response = SoftEtherProtocol::Deserialize($responseBuffer);

			return $response;
		}

		public static function CreateUserHashAndNtLm (string $name, string $password) : SoftEtherHashPair {
			$hashedPw = self::CreateUserPasswordHash($name, $password);
			$ntlmHash = self::CreateNtlmHash($password);
			return new SoftEtherHashPair($hashedPw, $ntlmHash);
		}

		public static function CreateUserPasswordHash (string $username, string $password) : array {
			$hashCreator = new SHA0();
			$hashCreator->Update(unpack('C*', $password));
			$hashedPw = $hashCreator->Update(unpack('C*', strtoupper($username)))->Digest();
			return $hashedPw;
		}

		public static function CreateNtlmHash (string $password) {
			$hashPrepare = iconv('UTF-8', 'UTF-16LE', $password);
			$ntlmHash = hash('md4', $hashPrepare, true);
			return unpack('C*', $ntlmHash);
		}

		public function CreateHashAnSecure (string $password) : SoftEtherHashPair {
			$hashedPw = self::CreatePasswordHash($password);
			$saltedPw = self::CreateSaltedHash($hashedPw, $this->RandomFromServer);

			return new SoftEtherHashPair($hashedPw, $saltedPw);
		}

		public static function CreatePasswordHash (string $password) : array {
			$hashCreator = new SHA0();
			$hashedPw = $hashCreator->Update(unpack('C*', $password))->Digest();

			return $hashedPw;
		}

		public static function CreateSaltedHash (array $passwordHash, $salt) : array {
			$hashCreator = new SHA0();
			$hashCreator->Update($passwordHash);
			$saltedPw = $hashCreator->Update($salt)->Digest();

			return $saltedPw;
		}
	}

<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Api;

	use SoftEtherApi\SoftEther;
	use SoftEtherApi\Containers\SoftEtherValueType;
	use SoftEtherApi\SoftEtherModel\ConnectionInfo;
	use SoftEtherApi\SoftEtherModel\ConnectionList;
	use SoftEtherApi\SoftEtherModel\L2tpSettings;
	use SoftEtherApi\SoftEtherModel\PortListenerList;
	use SoftEtherApi\SoftEtherModel\ServerCert;
	use SoftEtherApi\SoftEtherModel\ServerCipher;
	use SoftEtherApi\SoftEtherModel\ServerInfo;
	use SoftEtherApi\SoftEtherModel\ServerStatus;

	class SoftEtherServer {
		private $softEther;

		public function __construct(SoftEther $softEther) {
			$this->softEther = $softEther;
		}

		public function GetInfo () {
			$rawData = $this->softEther->CallMethod('GetServerInfo');
			return ServerInfo::Deserialize($rawData);
		}

		public function GetStatus () {
			$rawData = $this->softEther->CallMethod('GetServerStatus');
			return ServerStatus::Deserialize($rawData);
		}

		public function GetPortListenerList () {
			$rawData = $this->softEther->CallMethod('EnumListener');
			return PortListenerList::DeserializeMany($rawData);
		}

		public function GetCert () {
			$rawData = $this->softEther->CallMethod('GetServerCert');
			return ServerCert::Deserialize($rawData);
		}

		public function GetL2tpSettings () {
			$rawData = $this->softEther->CallMethod('GetIPsecServices');
			return L2tpSettings::Deserialize($rawData);
		}

		public function SetL2tpSettings ($settings) {
			$requestData = [
				'L2TP_Raw'        => ['type' => SoftEtherValueType::Int,   'value' => $settings->L2TP_Raw],
				'L2TP_IPsec'      => ['type' => SoftEtherValueType::Int,   'value' => $settings->L2TP_IPsec],
				'EtherIP_IPsec'   => ['type' => SoftEtherValueType::Int,   'value' => $settings->EtherIP_IPsec],
				'L2TP_DefaultHub' => ['type' => SoftEtherValueType::String,'value' => $settings->L2TP_DefaultHub],
				'IPsec_Secret'    => ['type' => SoftEtherValueType::String,'value' => $settings->IPsec_Secret]
			];

			$rawData = $this->softEther->CallMethod('SetIPsecServices', $requestData);
			return L2tpSettings::Deserialize($rawData);
		}

		public function GetCipher () {
			$rawData = $this->softEther->CallMethod('GetServerCipher');
			return ServerCipher::Deserialize($rawData);
		}

		public function GetConnectionList () {
			$rawData = $this->softEther->CallMethod('EnumConnection');
			return ConnectionList::DeserializeMany($rawData);
		}

		public function GetConnectionInfo ($name) {
			$requestData = [
				'Name' => ['type' => SoftEtherValueType::String, 'value'[$name]]
			];

			$rawData = $this->softEther->CallMethod('GetConnectionInfo', $requestData);
			return ConnectionInfo::Deserialize($rawData);
		}
	}

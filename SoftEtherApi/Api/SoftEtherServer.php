<?php

namespace SoftEtherApi\Api
{
    use SoftEtherApi;
    use SoftEtherApi\Containers;
    use SoftEtherApi\SoftEtherModel;

    class SoftEtherServer
    {
        private $softEther;

        public function __construct(SoftEtherApi\SoftEther $softEther)
        {
            $this->softEther = $softEther;
        }

        public function GetInfo()
        {
            $rawData = $this->softEther->CallMethod('GetServerInfo');
            return SoftEtherModel\ServerInfo::Deserialize($rawData);
        }

        public function GetStatus()
        {
            $rawData = $this->softEther->CallMethod('GetServerStatus');
            return SoftEtherModel\ServerStatus::Deserialize($rawData);
        }

        public function GetPortListenerList()
        {
            $rawData = $this->softEther->CallMethod('EnumListener');
            return SoftEtherModel\PortListenerList::DeserializeMany($rawData);
        }

        public function GetCert()
        {
            $rawData = $this->softEther->CallMethod('GetServerCert');
            return SoftEtherModel\ServerCert::Deserialize($rawData);
        }
        
        public function GetL2tpSettings()
        {
            $rawData = $this->softEther->CallMethod('GetIPsecServices');
            return SoftEtherModel\L2tpSettings::Deserialize($rawData);
        }
        
        public function SetL2tpSettings($settings)
        {
            $requestData = [
                'L2TP_Raw' => ['type' => Containers\SoftEtherValueType::Int,'value' => $settings->L2TP_Raw],
                'L2TP_IPsec' => ['type' => Containers\SoftEtherValueType::Int,'value' => $settings->L2TP_IPsec],
                'EtherIP_IPsec' => ['type' => Containers\SoftEtherValueType::Int,'value' => $settings->EtherIP_IPsec],
                'L2TP_DefaultHub' => ['type' => Containers\SoftEtherValueType::String,'value' => $settings->L2TP_DefaultHub],
                'IPsec_Secret' => ['type' => Containers\SoftEtherValueType::String,'value' => $settings->IPsec_Secret]
            ];
            
            $rawData = $this->softEther->CallMethod('SetIPsecServices', $requestData);
            return SoftEtherModel\L2tpSettings::Deserialize($rawData);
        }

        public function GetCipher()
        {
            $rawData = $this->softEther->CallMethod('GetServerCipher');
            return SoftEtherModel\ServerCipher::Deserialize($rawData);
        }

        public function GetConnectionList()
        {
            $rawData = $this->softEther->CallMethod('EnumConnection');
            return SoftEtherModel\ConnectionList::DeserializeMany($rawData);
        }

        public function GetConnectionInfo($name)
        {
            $requestData = [
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value'[$name]]
            ];

            $rawData = $this->softEther->CallMethod('GetConnectionInfo', $requestData);
            return SoftEtherModel\ConnectionInfo::Deserialize($rawData);
        }
    }
}
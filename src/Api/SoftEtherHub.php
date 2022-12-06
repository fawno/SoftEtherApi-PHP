<?php

namespace SoftEtherApi\Api {
    use SoftEtherApi;
    use SoftEtherApi\Containers;
    use SoftEtherApi\Infrastructure;
    use SoftEtherApi\Model;
    use SoftEtherApi\SoftEtherModel;

    class SoftEtherHub
    {
        private $softEther;

        public function __construct(SoftEtherApi\SoftEther $softEther)
        {
            $this->softEther = $softEther;
        }

        public function SetOnline($hubName, $online)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Online' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$online]],
            ];

            $rawData = $this->softEther->CallMethod('SetHubOnline', $requestData);
            return SoftEtherModel\Hub::Deserialize($rawData);
        }

        public function Get($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHub', $requestData);
            return SoftEtherModel\Hub::Deserialize($rawData);
        }

        public function EnableSecureNat($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnableSecureNAT', $requestData);
            return SoftEtherModel\SoftEtherResult::Deserialize($rawData);
        }

        public function DisableSecureNat($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('DisableSecureNAT', $requestData);
            return SoftEtherModel\SoftEtherResult::Deserialize($rawData);
        }

        public function GetSecureNatOptions($hubName)
        {
            $requestData = [
                'RpcHubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetSecureNATOption', $requestData);
            $model = SoftEtherModel\VirtualHostOptions::Deserialize($rawData);
            $model->RpcHubName = $hubName; //Fix, as softEther clears the hubname
            return $model;
        }

        public function SetSecureNatDhcpPushRoutes($hubName, $routes)
        {
            $options = $this->GetSecureNatOptions($hubName);
            if (!$options->Valid())
                return $options;

            $options->DhcpPushRoutes = $routes;
            return $this->SetSecureNatOptions($hubName, $options);
        }

        public function SetSecureNatOptions($options)
        {
            $requestData = [
                'RpcHubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$options->RpcHubName]],
                'MacAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->MacAddress]],
                'Ip' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->Ip]],
                'Mask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->Mask]],
                'UseNat' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->UseNat]],
                'Mtu' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->Mtu]],
                'NatTcpTimeout' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->NatTcpTimeout]],
                'NatUdpTimeout' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->NatUdpTimeout]],
                'UseDhcp' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->UseDhcp]],
                'DhcpLeaseIPStart' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpLeaseIPStart]],
                'DhcpLeaseIPEnd' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpLeaseIPEnd]],
                'DhcpSubnetMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpSubnetMask]],
                'DhcpExpireTimeSpan' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpExpireTimeSpan]],
                'DhcpGatewayAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpGatewayAddress]],
                'DhcpDnsServerAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpDnsServerAddress]],
                'DhcpDnsServerAddress2' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->DhcpDnsServerAddress2]],
                'DhcpDomainName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$options->DhcpDomainName]],
                'SaveLog' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->SaveLog]],
                'ApplyDhcpPushRoutes' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$options->ApplyDhcpPushRoutes]],
                'DhcpPushRoutes' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$options->DhcpPushRoutes->ToString()]]
            ];

            $rawData = $this->softEther->CallMethod('SetSecureNATOption', $requestData);
            return SoftEtherModel\VirtualHostOptions::Deserialize($rawData);
        }

        public function GetList()
        {
            $rawData = $this->softEther->CallMethod('EnumHub');
            return SoftEtherModel\HubList::DeserializeMany($rawData);
        }

        public function GetRadius($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubRadius', $requestData);
            return SoftEtherModel\HubRadius::Deserialize($rawData);
        }

        public function GetStatus($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubStatus', $requestData);
            return SoftEtherModel\HubStatus::Deserialize($rawData);
        }

        public function GetLog($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubLog', $requestData);
            return SoftEtherModel\HubLog::Deserialize($rawData);
        }

        public function GetAccessList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumAccess', $requestData);
            return SoftEtherModel\HubAccessList::DeserializeMany($rawData);
        }

        public function AddAccessList($hubName, $accessList)
        {
            return array_map(function ($x) use ($hubName) {
                return $this->AddAccessList2($hubName, $x);
            }, $accessList);
        }

        public function AddAccessList2($hubName, $accessList)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Id' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Id]],
                'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$accessList->Note]],
                'Active' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Active]],
                'Priority' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Priority]],
                'Discard' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Discard]],
                'SrcIpAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcIpAddress]],
                'SrcSubnetMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcSubnetMask]],
                'DestIpAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DestIpAddress]],
                'DestSubnetMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DestSubnetMask]],
                'Protocol' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Protocol]],
                'SrcPortStart' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcPortStart]],
                'SrcPortEnd' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcPortEnd]],
                'DestPortStart' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DestPortStart]],
                'DestPortEnd' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DestPortEnd]],
                'SrcUsername' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$accessList->SrcUsername]],
                'DestUsername' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$accessList->DestUsername]],
                'CheckSrcMac' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->CheckSrcMac]],
                'SrcMacAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcMacAddress]],
                'SrcMacMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->SrcMacMask]],
                'CheckDstMac' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->CheckDstMac]],
                'DstMacAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DstMacAddress]],
                'DstMacMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->DstMacMask]],
                'CheckTcpState' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->CheckTcpState]],
                'Established' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Established]],
                'Delay' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Delay]],
                'Jitter' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Jitter]],
                'Loss' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->Loss]],
                'IsIPv6' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->IsIPv6]],
                'UniqueId' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$accessList->UniqueId]],
                'RedirectUrl' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$accessList->RedirectUrl]],
            ];

            $rawData = $this->softEther->CallMethod('AddAccess', $requestData);
            return SoftEtherModel\HubAccessList::Deserialize($rawData);
        }

        public function SetAccessList($hubName, $accessList)
        {
            return array_map(function ($x) use ($hubName) {
                return $this->SetAccessList2($hubName, $x);
            }, $accessList);
        }

        public function SetAccessList2($hubName, $accessList)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Id' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Id;}, $accessList)],
                'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => array_map(function ($x) {return $x->Id;}, $accessList)],
                'Active' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Active;}, $accessList)],
                'Priority' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Priority;}, $accessList)],
                'Discard' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Discard;}, $accessList)],
                'SrcIpAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcIpAddress;}, $accessList)],
                'SrcSubnetMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcSubnetMask;}, $accessList)],
                'DestIpAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DestIpAddress;}, $accessList)],
                'DestSubnetMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DestSubnetMask;}, $accessList)],
                'Protocol' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Protocol;}, $accessList)],
                'SrcPortStart' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcPortStart;}, $accessList)],
                'SrcPortEnd' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcPortEnd;}, $accessList)],
                'DestPortStart' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DestPortStart;}, $accessList)],
                'DestPortEnd' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DestPortEnd;}, $accessList)],
                'SrcUsername' => ['type' => Containers\SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcUsername;}, $accessList)],
                'DestUsername' => ['type' => Containers\SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestUsername;}, $accessList)],
                'CheckSrcMac' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->CheckSrcMac;}, $accessList)],
                'SrcMacAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcMacAddress;}, $accessList)],
                'SrcMacMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->SrcMacMask;}, $accessList)],
                'CheckDstMac' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->CheckDstMac;}, $accessList)],
                'DstMacAddress' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DstMacAddress;}, $accessList)],
                'DstMacMask' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->DstMacMask;}, $accessList)],
                'CheckTcpState' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->CheckTcpState;}, $accessList)],
                'Established' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Established;}, $accessList)],
                'Delay' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Delay;}, $accessList)],
                'Jitter' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Jitter;}, $accessList)],
                'Loss' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->Loss;}, $accessList)],
                'IsIPv6' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->IsIPv6;}, $accessList)],
                'UniqueId' => ['type' => Containers\SoftEtherValueType::Int, 'value' => array_map(function ($x) { return $x->UniqueId;}, $accessList)],
                'RedirectUrl' => ['type' => Containers\SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->RedirectUrl;}, $accessList)],
            ];

            $rawData = $this->softEther->CallMethod('SetAccessList', $requestData);
            return SoftEtherModel\HubAccessList::DeserializeMany($rawData);
        }

        public function GetSessionList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumSession', $requestData);
            return SoftEtherModel\HubSessionList::DeserializeMany($rawData);
        }

        public function GetSessionStatus($hubName, $sessionName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$sessionName]],
            ];

            $rawData = $this->softEther->CallMethod('GetSessionStatus', $requestData);
            return SoftEtherModel\HubSessionStatus::Deserialize($rawData);
        }

        public function DisconnectSession($hubName, $sessionName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$sessionName]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteSession', $requestData);
            return SoftEtherModel\SoftEtherResult::Deserialize($rawData);
        }

        public function GetUserList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumUser', $requestData);
            return SoftEtherModel\HubUserList::DeserializeMany($rawData);
        }

        public function GetUser($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('GetUser', $requestData);
            return SoftEtherModel\HubUser::Deserialize($rawData);
        }

        public function GetGroup($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('GetGroup', $requestData);
            return SoftEtherModel\HubGroup::Deserialize($rawData);
        }

        public function DeleteGroup($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteGroup', $requestData);
            return SoftEtherModel\HubGroup::Deserialize($rawData);
        }

        public function Delete($hubName)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteHub', $requestData);
            return SoftEtherModel\Hub::Deserialize($rawData);
        }

        public function Create($name, $password, $online, $noAnonymousEnumUser = true,
                               $hubType = Model\HubType::Standalone, $maxSession = 0)
        {
            $hashPair = $this->softEther->CreateHashAnSecure($password);

            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
                'HashedPassword' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$hashPair->Hash]],
                'SecurePassword' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$hashPair->SaltedHash]],
                'Online' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$online]],
                'MaxSession' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$maxSession]],
                'NoEnum' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$noAnonymousEnumUser]],
                'HubType' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [(int)$hubType]],
            ];

            $rawData = $this->softEther->CallMethod('CreateHub', $requestData);
            return SoftEtherModel\Hub::Deserialize($rawData);
        }

        public function CreateGroup($hubName, $name, $realName = null, $note = null)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
                'Realname' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value'  => [$note]],
            ];

            $rawData = $this->softEther->CallMethod('CreateGroup', $requestData);
            return SoftEtherModel\HubGroup::Deserialize($rawData);
        }

        public function SetGroup2($hubName, $name, $realName, $note)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
                'Realname' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$note]],
            ];

            $rawData = $this->softEther->CallMethod('SetGroup', $requestData);
            return SoftEtherModel\HubGroup::Deserialize($rawData);
        }

        public function SetGroup($hubName, $group)
        {
            return $this->SetGroup2($hubName, $group->Name, $group->Realname, $group->Note);
        }

        public function ChangeGroupNote($hubName, $name, $note)
        {
            $group = $this->GetGroup($hubName, $name);
            $group->Note = $note;
            return $this->SetGroup($hubName, $group);
        }

        public function CreateUser($hubName, $name, $password, $groupName = null,
                                   $realName = null, $note = null, $expireTime = null)
        {
            $hashPair = $this->softEther->CreateUserHashAndNtLm($name, $password);

            $requestData =
                [
                    'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                    'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
                    'GroupName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$groupName]],
                    'Realname' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                    'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$note]],
                    'ExpireTime' => ['type' => Containers\SoftEtherValueType::Int64, 'value' => [Infrastructure\SoftEtherConverter::DateTimeToSoftEtherLong($expireTime)]],
                    'AuthType' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [Model\AuthType::Password]],
                    'HashedKey' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$hashPair->Hash]],
                    'NtLmSecureHash' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$hashPair->SaltedHash]],
                ];

            $rawData = $this->softEther->CallMethod('CreateUser', $requestData);
            return SoftEtherModel\HubUser::Deserialize($rawData);
        }

        public function SetUser($hubName, $user)
        {
            return $this->SetUser2($hubName, $user->Name, $user->GroupName, $user->Realname, $user->Note,
                $user->CreatedTime, $user->UpdatedTime, $user->ExpireTime,
                $user->NumLogin, $user->AuthType, $user->HashedKey, $user->NtLmSecureHash);
        }

        public function SetUser2($hubName, $name,
                                 $groupName,
                                 $realName, $note,
                                 $createTime,
                                 $updatedTime,
                                 $expireTime,
                                 $numLogin,
                                 $authType,
                                 $hashedPw,
                                 $securePw)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
                'GroupName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$groupName]],
                'Realname' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                'Note' => ['type' => Containers\SoftEtherValueType::UnicodeString, 'value' => [$note]],
                'CreatedTime' => ['type' => Containers\SoftEtherValueType::Int64, 'value' => [$createTime]],
                'UpdatedTime' => ['type' => Containers\SoftEtherValueType::Int64, 'value' => [$updatedTime]],
                'ExpireTime' => ['type' => Containers\SoftEtherValueType::Int64, 'value' => [$expireTime]],
                'NumLogin' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [$numLogin]],
                'AuthType' => ['type' => Containers\SoftEtherValueType::Int, 'value' => [(int)$authType]],
                'HashedKey' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$hashedPw]],
                'NtLmSecureHash' => ['type' => Containers\SoftEtherValueType::Raw, 'value' => [$securePw]],
            ];

            $rawData = $this->softEther->CallMethod('SetUser', $requestData);
            return SoftEtherModel\HubUser::Deserialize($rawData);
        }

        public function DeleteUser($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteUser', $requestData);
            return SoftEtherModel\HubUser::Deserialize($rawData);
        }

        public function SetUserExpireDate($hubName, $name, $expireDate)
        {
            $user = $this->GetUser($hubName, $name);
            if($user->NotValid())
                return $user;

            $user->ExpireTime = Infrastructure\SoftEtherConverter::DateTimeToSoftEtherLong($expireDate);
            return $this->SetUser($hubName, $user);
        }

        public function SetUserPassword($hubName, $name, $password)
        {
            $user = $this->GetUser($hubName, $name);
            if($user->NotValid())
                return $user;

            $hashPair = $this->softEther->CreateUserHashAndNtLm($name, $password);

            $user->HashedKey = $hashPair->Hash;
            $user->NtLmSecureHash = $hashPair->SaltedHash;

            return $this->SetUser($hubName, $user);
        }
    }
}
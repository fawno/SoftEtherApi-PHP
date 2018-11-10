<?php

namespace SoftEtherApi\Api {
    require_once('SoftEtherModel/HubStatus.php');
    require_once('SoftEtherModel/HubUser.php');
    require_once('Model/AuthType.php');
    require_once('Infrastructure/SoftEtherConverter.php');

    use SoftEtherApi\Containers\SoftEtherValueType;
    use SoftEtherApi\Infrastructure\SoftEtherConverter;
    use SoftEtherApi\Model\AuthType;
    use SoftEtherApi\Model\HubType;
    use SoftEtherApi\SoftEtherModel\Hub;
    use SoftEtherApi\SoftEtherModel\HubAccessList;
    use SoftEtherApi\SoftEtherModel\HubGroup;
    use SoftEtherApi\SoftEtherModel\HubList;
    use SoftEtherApi\SoftEtherModel\HubLog;
    use SoftEtherApi\SoftEtherModel\HubRadius;
    use SoftEtherApi\SoftEtherModel\HubSessionList;
    use SoftEtherApi\SoftEtherModel\HubSessionStatus;
    use SoftEtherApi\SoftEtherModel\HubStatus;
    use SoftEtherApi\SoftEtherModel\HubUser;
    use SoftEtherApi\SoftEtherModel\HubUserList;
    use SoftEtherApi\SoftEtherModel\SoftEtherResult;
    use SoftEtherApi\SoftEtherModel\VirtualHostOptions;

    class SoftEtherHub
    {
        private $softEther;

        public function __construct($softEther)
        {
            $this->softEther = $softEther;
        }

        public function SetOnline($hubName, $online)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Online' => ['type' => SoftEtherValueType::String, 'value' => [$online]],
            ];

            $rawData = $this->softEther->CallMethod('SetHubOnline', $requestData);
            return Hub::Deserialize($rawData);
        }

        public function Get($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHub', $requestData);
            return Hub::Deserialize($rawData);
        }

        public function EnableSecureNat($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnableSecureNAT', $requestData);
            return SoftEtherResult::Deserialize($rawData);
        }

        public function DisableSecureNat($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('DisableSecureNAT', $requestData);
            return SoftEtherResult::Deserialize($rawData);
        }

        public function GetSecureNatOptions($hubName)
        {
            $requestData = [
                'RpcHubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetSecureNATOption', $requestData);
            $model = VirtualHostOptions::Deserialize($rawData);
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

        public function SetSecureNatOptions($hubName, $options)
        {
            $requestData = [
                'RpcHubName' => ['type' => SoftEtherValueType::String, 'value' => [$options->RpcHubName]],
                'MacAddress' => ['type' => SoftEtherValueType::String, 'value' => [$options->MacAddress]],
                'Ip' => ['type' => SoftEtherValueType::String, 'value' => [$options->Ip]],
                'Mask' => ['type' => SoftEtherValueType::String, 'value' => [$options->Mask]],
                'UseNat' => ['type' => SoftEtherValueType::String, 'value' => [$options->UseNat]],
                'Mtu' => ['type' => SoftEtherValueType::String, 'value' => [$options->Mtu]],
                'NatTcpTimeout' => ['type' => SoftEtherValueType::String, 'value' => [$options->NatTcpTimeout]],
                'NatUdpTimeout' => ['type' => SoftEtherValueType::String, 'value' => [$options->NatUdpTimeout]],
                'UseDhcp' => ['type' => SoftEtherValueType::String, 'value' => [$options->UseDhcp]],
                'DhcpLeaseIPStart' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpLeaseIPStart]],
                'DhcpLeaseIPEnd' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpLeaseIPEnd]],
                'DhcpSubnetMask' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpSubnetMask]],
                'DhcpExpireTimeSpan' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpExpireTimeSpan]],
                'DhcpGatewayAddress' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpGatewayAddress]],
                'DhcpDnsServerAddress' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpDnsServerAddress]],
                'DhcpDnsServerAddress2' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpDnsServerAddress2]],
                'DhcpDomainName' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpDomainName]],
                'SaveLog' => ['type' => SoftEtherValueType::String, 'value' => [$options->SaveLog]],
                'ApplyDhcpPushRoutes' => ['type' => SoftEtherValueType::String, 'value' => [$options->ApplyDhcpPushRoutes]],
                'DhcpPushRoutes' => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpPushRoutes->ToString()]]
            ];

            $rawData = $this->softEther->CallMethod('SetSecureNATOption', $requestData);
            return VirtualHostOptions::Deserialize($rawData);
        }

        public function GetList()
        {
            $rawData = $this->softEther->CallMethod('EnumHub');
            return HubList::DeserializeMany($rawData);
        }

        public function GetRadius($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubRadius', $requestData);
            return HubRadius::Deserialize($rawData);
        }

        public function GetStatus($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubStatus', $requestData);
            return HubStatus::Deserialize($rawData);
        }

        public function GetLog($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('GetHubLog', $requestData);
            return HubLog::Deserialize($rawData);
        }

        public function GetAccessList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumAccess', $requestData);
            return HubAccessList::DeserializeMany($rawData);
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
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Id' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Id]],
                'Note' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$accessList->Note]],
                'Active' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Active]],
                'Priority' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Priority]],
                'Discard' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Discard]],
                'SrcIpAddress' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcIpAddress]],
                'SrcSubnetMask' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcSubnetMask]],
                'DestIpAddress' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DestIpAddress]],
                'DestSubnetMask' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DestSubnetMask]],
                'Protocol' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Protocol]],
                'SrcPortStart' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcPortStart]],
                'SrcPortEnd' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcPortEnd]],
                'DestPortStart' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DestPortStart]],
                'DestPortEnd' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DestPortEnd]],
                'SrcUsername' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcUsername]],
                'DestUsername' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DestUsername]],
                'CheckSrcMac' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->CheckSrcMac]],
                'SrcMacAddress' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcMacAddress]],
                'SrcMacMask' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->SrcMacMask]],
                'CheckDstMac' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->CheckDstMac]],
                'DstMacAddress' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DstMacAddress]],
                'DstMacMask' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->DstMacMask]],
                'CheckTcpState' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->CheckTcpState]],
                'Established' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Established]],
                'Delay' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Delay]],
                'Jitter' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Jitter]],
                'Loss' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->Loss]],
                'IsIPv6' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->IsIPv6]],
                'UniqueId' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->UniqueId]],
                'RedirectUrl' => ['type' => SoftEtherValueType::String, 'value' => [$accessList->RedirectUrl]],
            ];

            $rawData = $this->softEther->CallMethod('AddAccess', $requestData);
            return HubAccessList::Deserialize($rawData);
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
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Id' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Id;}, $accessList)],
                'Note' => ['type' => SoftEtherValueType::UnicodeString, 'value' => array_map(function ($x) {return $x->Id;}, $accessList)],
                'Active' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Active;}, $accessList)],
                'Priority' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Priority;}, $accessList)],
                'Discard' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Discard;}, $accessList)],
                'SrcIpAddress' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcIpAddress;}, $accessList)],
                'SrcSubnetMask' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcSubnetMask;}, $accessList)],
                'DestIpAddress' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestIpAddress;}, $accessList)],
                'DestSubnetMask' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestSubnetMask;}, $accessList)],
                'Protocol' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Protocol;}, $accessList)],
                'SrcPortStart' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcPortStart;}, $accessList)],
                'SrcPortEnd' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcPortEnd;}, $accessList)],
                'DestPortStart' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestPortStart;}, $accessList)],
                'DestPortEnd' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestPortEnd;}, $accessList)],
                'SrcUsername' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcUsername;}, $accessList)],
                'DestUsername' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DestUsername;}, $accessList)],
                'CheckSrcMac' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->CheckSrcMac;}, $accessList)],
                'SrcMacAddress' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcMacAddress;}, $accessList)],
                'SrcMacMask' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->SrcMacMask;}, $accessList)],
                'CheckDstMac' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->CheckDstMac;}, $accessList)],
                'DstMacAddress' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DstMacAddress;}, $accessList)],
                'DstMacMask' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->DstMacMask;}, $accessList)],
                'CheckTcpState' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->CheckTcpState;}, $accessList)],
                'Established' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Established;}, $accessList)],
                'Delay' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Delay;}, $accessList)],
                'Jitter' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Jitter;}, $accessList)],
                'Loss' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->Loss;}, $accessList)],
                'IsIPv6' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->IsIPv6;}, $accessList)],
                'UniqueId' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->UniqueId;}, $accessList)],
                'RedirectUrl' => ['type' => SoftEtherValueType::String, 'value' => array_map(function ($x) { return $x->RedirectUrl;}, $accessList)],
            ];

            $rawData = $this->softEther->CallMethod('SetAccessList', $requestData);
            return HubAccessList::DeserializeMany($rawData);
        }

        public function GetSessionList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumSession', $requestData);
            return HubSessionList::DeserializeMany($rawData);
        }

        public function GetSessionStatus($hubName, $sessionName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$sessionName]],
            ];

            $rawData = $this->softEther->CallMethod('GetSessionStatus', $requestData);
            return HubSessionStatus::Deserialize($rawData);
        }

        public function DisconnectSession($hubName, $sessionName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$sessionName]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteSession', $requestData);
            return SoftEtherResult::Deserialize($rawData);
        }

        public function GetUserList($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('EnumUser', $requestData);
            return HubUserList::DeserializeMany($rawData);
        }

        public function GetUser($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('GetUser', $requestData);
            return HubUser::Deserialize($rawData);
        }

        public function GetGroup($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('GetGroup', $requestData);
            return HubGroup::Deserialize($rawData);
        }

        public function DeleteGroup($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteGroup', $requestData);
            return HubGroup::Deserialize($rawData);
        }

        public function Delete($hubName)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteHub', $requestData);
            return Hub::Deserialize($rawData);
        }

        public function Create($name, $password, $online, $noAnonymousEnumUser = true,
                               $hubType = HubType::Standalone, $maxSession = 0)
        {
            $hashPair = $this->softEther->CreateHashAnSecure($password);

            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
                'HashedPassword' => ['type' => SoftEtherValueType::String, 'value' => [$hashPair->Hash]],
                'SecurePassword' => ['type' => SoftEtherValueType::String, 'value' => [$hashPair->SaltedHash]],
                'Online' => ['type' => SoftEtherValueType::String, 'value' => [$online]],
                'MaxSession' => ['type' => SoftEtherValueType::String, 'value' => [$maxSession]],
                'NoEnum' => ['type' => SoftEtherValueType::String, 'value' => [$noAnonymousEnumUser]],
                'HubType' => ['type' => SoftEtherValueType::String, 'value' => [(int)$hubType]],
            ];

            $rawData = $this->softEther->CallMethod('CreateHub', $requestData);
            return Hub::Deserialize($rawData);
        }

        public function CreateGroup($hubName, $name, $realName = null, $note = null)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
                'Realname' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                'Note' => ['type' => SoftEtherValueType::UnicodeString, 'value'  => [$note]],
            ];

            $rawData = $this->softEther->CallMethod('CreateGroup', $requestData);
            return HubGroup::Deserialize($rawData);
        }

        public function SetGroup2($hubName, $name, $realName, $note)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
                'Realname' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
                'Note' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$note]],
            ];

            $rawData = $this->softEther->CallMethod('SetGroup', $requestData);
            return HubGroup::Deserialize($rawData);
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
                    'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                    'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
                    'GroupName' => ['type' => SoftEtherValueType::String, 'value' => [$groupName]],
                    'Realname' => ['type' => SoftEtherValueType::String, 'value' => [$realName]],
                    'Note' => ['type' => SoftEtherValueType::String, 'value' => [$note]],
                    'ExpireTime' => ['type' => SoftEtherValueType::Int64, 'value' => [SoftEtherConverter::DateTimeToSoftEtherLong($expireTime)]],
                    'AuthType' => ['type' => SoftEtherValueType::Int, 'value' => [AuthType::Password]],
                    'HashedKey' => ['type' => SoftEtherValueType::Raw, 'value' => [$hashPair->Hash]],
                    'NtLmSecureHash' => ['type' => SoftEtherValueType::Raw, 'value' => [$hashPair->SaltedHash]],
                ];

            $rawData = $this->softEther->CallMethod('CreateUser', $requestData);
            return HubUser::Deserialize($rawData);
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
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
                'GroupName' => ['type' => SoftEtherValueType::String, 'value' => [$groupName]],
                'Realname' => ['type' => SoftEtherValueType::String, 'value' => [$realName]],
                'Note' => ['type' => SoftEtherValueType::String, 'value' => [$note]],
                'CreatedTime' => ['type' => SoftEtherValueType::String, 'value' => [$createTime]],
                'UpdatedTime' => ['type' => SoftEtherValueType::String, 'value' => [$updatedTime]],
                'ExpireTime' => ['type' => SoftEtherValueType::String, 'value' => [$expireTime]],
                'NumLogin' => ['type' => SoftEtherValueType::String, 'value' => [$numLogin]],
                'AuthType' => ['type' => SoftEtherValueType::String, 'value' => [(int)$authType]],
                'HashedKey' => ['type' => SoftEtherValueType::String, 'value' => [$hashedPw]],
                'NtLmSecureHash' => ['type' => SoftEtherValueType::String, 'value' => [$securePw]],
            ];

            $rawData = $this->softEther->CallMethod('SetUser', $requestData);
            return HubUser::Deserialize($rawData);
        }

        public function DeleteUser($hubName, $name)
        {
            $requestData = [
                'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
                'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
            ];

            $rawData = $this->softEther->CallMethod('DeleteUser', $requestData);
            return HubUser::Deserialize($rawData);
        }

        public function SetUserExpireDate($hubName, $name, $expireDate)
        {
            $user = $this->GetUser($hubName, $name);
            $user->ExpireTime = $expireDate;
            return $this->SetUser($hubName, $user);
        }

        public function SetUserPassword($hubName, $name, $password)
        {
            $user = $this->GetUser($hubName, $name);
            $hashPair = $this->softEther->CreateUserHashAndNtLm($name, $password);

            $user->HashedKey = $hashPair->Hash;
            $user->NtLmSecureHash = $hashPair->SaltedHash;

            return $this->SetUser($hubName, $user);
        }
    }
}
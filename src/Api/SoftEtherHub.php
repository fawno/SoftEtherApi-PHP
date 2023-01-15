<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Api;

	use DateTime;
	use SoftEtherApi\Containers;
	use SoftEtherApi\Containers\SoftEtherList;
	use SoftEtherApi\Containers\SoftEtherValueType;
	use SoftEtherApi\Infrastructure\SoftEtherConverter;
	use SoftEtherApi\Model\AuthType;
	use SoftEtherApi\Model\HubType;
	use SoftEtherApi\SoftEther;
	use SoftEtherApi\SoftEtherModel;
	use SoftEtherApi\SoftEtherModel\Hub;
	use SoftEtherApi\SoftEtherModel\HubAccessList;
	use SoftEtherApi\SoftEtherModel\HubGroup;
	use SoftEtherApi\SoftEtherModel\HubGroupList;
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

	class SoftEtherHub {
		private $softEther;

		public function __construct (SoftEther $softEther) {
			$this->softEther = $softEther;
		}

		public function SetOnline (string  $hubName, int $online) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Online' => ['type' => SoftEtherValueType::Int, 'value' => [$online]],
			];

			$rawData = $this->softEther->CallMethod('SetHubOnline', $requestData);
			return Hub::Deserialize($rawData);
		}

		public function Get (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('GetHub', $requestData);
			return Hub::Deserialize($rawData);
		}

		public function EnableSecureNat (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('EnableSecureNAT', $requestData);
			return SoftEtherResult::Deserialize($rawData);
		}

		public function DisableSecureNat (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('DisableSecureNAT', $requestData);
			return SoftEtherResult::Deserialize($rawData);
		}

		public function GetSecureNatOptions (string $hubName) {
			$requestData = [
				'RpcHubName' => ['type' => Containers\SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('GetSecureNATOption', $requestData);
			$model = VirtualHostOptions::Deserialize($rawData);
			$model->RpcHubName = $hubName; //Fix, as softEther clears the hubname
			return $model;
		}

		public function SetSecureNatDhcpPushRoutes (string $hubName, $routes) {
			$options = $this->GetSecureNatOptions($hubName);
			if (!$options->Valid())
					return $options;

			$options->DhcpPushRoutes = $routes;
			return $this->SetSecureNatOptions($hubName, $options);
		}

		public function SetSecureNatOptions ($options) {
			$requestData = [
				'RpcHubName'            => ['type' => SoftEtherValueType::String, 'value' => [$options->RpcHubName]],
				'MacAddress'            => ['type' => SoftEtherValueType::Int,    'value' => [$options->MacAddress]],
				'Ip'                    => ['type' => SoftEtherValueType::Int,    'value' => [$options->Ip]],
				'Mask'                  => ['type' => SoftEtherValueType::Int,    'value' => [$options->Mask]],
				'UseNat'                => ['type' => SoftEtherValueType::Int,    'value' => [$options->UseNat]],
				'Mtu'                   => ['type' => SoftEtherValueType::Int,    'value' => [$options->Mtu]],
				'NatTcpTimeout'         => ['type' => SoftEtherValueType::Int,    'value' => [$options->NatTcpTimeout]],
				'NatUdpTimeout'         => ['type' => SoftEtherValueType::Int,    'value' => [$options->NatUdpTimeout]],
				'UseDhcp'               => ['type' => SoftEtherValueType::Int,    'value' => [$options->UseDhcp]],
				'DhcpLeaseIPStart'      => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpLeaseIPStart]],
				'DhcpLeaseIPEnd'        => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpLeaseIPEnd]],
				'DhcpSubnetMask'        => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpSubnetMask]],
				'DhcpExpireTimeSpan'    => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpExpireTimeSpan]],
				'DhcpGatewayAddress'    => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpGatewayAddress]],
				'DhcpDnsServerAddress'  => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpDnsServerAddress]],
				'DhcpDnsServerAddress2' => ['type' => SoftEtherValueType::Int,    'value' => [$options->DhcpDnsServerAddress2]],
				'DhcpDomainName'        => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpDomainName]],
				'SaveLog'               => ['type' => SoftEtherValueType::Int,    'value' => [$options->SaveLog]],
				'ApplyDhcpPushRoutes'   => ['type' => SoftEtherValueType::Int,    'value' => [$options->ApplyDhcpPushRoutes]],
				'DhcpPushRoutes'        => ['type' => SoftEtherValueType::String, 'value' => [$options->DhcpPushRoutes->ToString()]]
			];

			$rawData = $this->softEther->CallMethod('SetSecureNATOption', $requestData);
			return VirtualHostOptions::Deserialize($rawData);
		}

		public function GetList () : SoftEtherList {
			$rawData = $this->softEther->CallMethod('EnumHub');
			return HubList::DeserializeMany($rawData);
		}

		public function GetRadius (string $hubName) : HubRadius {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('GetHubRadius', $requestData);
			return HubRadius::Deserialize($rawData);
		}

		public function GetStatus (string $hubName) : HubStatus {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('GetHubStatus', $requestData);
			return HubStatus::Deserialize($rawData);
		}

		public function GetLog (string $hubName) : HubLog {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('GetHubLog', $requestData);
			return HubLog::Deserialize($rawData);
		}

		public function GetAccessList (string $hubName) : SoftEtherList {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('EnumAccess', $requestData);
			return HubAccessList::DeserializeMany($rawData);
		}

		public function AddAccessList (string $hubName, $accessList) {
			return array_map(function ($x) use ($hubName) {
					return $this->AddAccessList2($hubName, $x);
			}, $accessList);
		}

		public function AddAccessList2 (string $hubName, $accessList) {
			$requestData = [
				'HubName'        => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Id'             => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Id]],
				'Note'           => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$accessList->Note]],
				'Active'         => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Active]],
				'Priority'       => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Priority]],
				'Discard'        => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Discard]],
				'SrcIpAddress'   => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcIpAddress]],
				'SrcSubnetMask'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcSubnetMask]],
				'DestIpAddress'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DestIpAddress]],
				'DestSubnetMask' => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DestSubnetMask]],
				'Protocol'       => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Protocol]],
				'SrcPortStart'   => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcPortStart]],
				'SrcPortEnd'     => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcPortEnd]],
				'DestPortStart'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DestPortStart]],
				'DestPortEnd'    => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DestPortEnd]],
				'SrcUsername'    => ['type' => SoftEtherValueType::String,        'value' => [$accessList->SrcUsername]],
				'DestUsername'   => ['type' => SoftEtherValueType::String,        'value' => [$accessList->DestUsername]],
				'CheckSrcMac'    => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->CheckSrcMac]],
				'SrcMacAddress'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcMacAddress]],
				'SrcMacMask'     => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->SrcMacMask]],
				'CheckDstMac'    => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->CheckDstMac]],
				'DstMacAddress'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DstMacAddress]],
				'DstMacMask'     => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->DstMacMask]],
				'CheckTcpState'  => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->CheckTcpState]],
				'Established'    => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Established]],
				'Delay'          => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Delay]],
				'Jitter'         => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Jitter]],
				'Loss'           => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->Loss]],
				'IsIPv6'         => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->IsIPv6]],
				'UniqueId'       => ['type' => SoftEtherValueType::Int,           'value' => [$accessList->UniqueId]],
				'RedirectUrl'    => ['type' => SoftEtherValueType::String,        'value' => [$accessList->RedirectUrl]],
			];

			$rawData = $this->softEther->CallMethod('AddAccess', $requestData);
			return HubAccessList::Deserialize($rawData);
		}

		public function SetAccessList (string $hubName, $accessList) {
			return array_map(function ($x) use ($hubName) {
				return $this->SetAccessList2($hubName, $x);
			}, $accessList);
		}

		public function SetAccessList2 (string $hubName, $accessList) {
			$requestData = [
				'HubName'        => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Id'             => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Id;}, $accessList)],
				'Note'           => ['type' => SoftEtherValueType::UnicodeString, 'value' => array_map(function ($x) {return $x->Id;}, $accessList)],
				'Active'         => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Active;}, $accessList)],
				'Priority'       => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Priority;}, $accessList)],
				'Discard'        => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Discard;}, $accessList)],
				'SrcIpAddress'   => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcIpAddress;}, $accessList)],
				'SrcSubnetMask'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcSubnetMask;}, $accessList)],
				'DestIpAddress'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DestIpAddress;}, $accessList)],
				'DestSubnetMask' => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DestSubnetMask;}, $accessList)],
				'Protocol'       => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Protocol;}, $accessList)],
				'SrcPortStart'   => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcPortStart;}, $accessList)],
				'SrcPortEnd'     => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcPortEnd;}, $accessList)],
				'DestPortStart'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DestPortStart;}, $accessList)],
				'DestPortEnd'    => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DestPortEnd;}, $accessList)],
				'SrcUsername'    => ['type' => SoftEtherValueType::String,        'value' => array_map(function ($x) { return $x->SrcUsername;}, $accessList)],
				'DestUsername'   => ['type' => SoftEtherValueType::String,        'value' => array_map(function ($x) { return $x->DestUsername;}, $accessList)],
				'CheckSrcMac'    => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->CheckSrcMac;}, $accessList)],
				'SrcMacAddress'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcMacAddress;}, $accessList)],
				'SrcMacMask'     => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->SrcMacMask;}, $accessList)],
				'CheckDstMac'    => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->CheckDstMac;}, $accessList)],
				'DstMacAddress'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DstMacAddress;}, $accessList)],
				'DstMacMask'     => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->DstMacMask;}, $accessList)],
				'CheckTcpState'  => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->CheckTcpState;}, $accessList)],
				'Established'    => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Established;}, $accessList)],
				'Delay'          => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Delay;}, $accessList)],
				'Jitter'         => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Jitter;}, $accessList)],
				'Loss'           => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->Loss;}, $accessList)],
				'IsIPv6'         => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->IsIPv6;}, $accessList)],
				'UniqueId'       => ['type' => SoftEtherValueType::Int,           'value' => array_map(function ($x) { return $x->UniqueId;}, $accessList)],
				'RedirectUrl'    => ['type' => SoftEtherValueType::String,        'value' => array_map(function ($x) { return $x->RedirectUrl;}, $accessList)],
			];

			$rawData = $this->softEther->CallMethod('SetAccessList', $requestData);
			return HubAccessList::DeserializeMany($rawData);
		}

		public function GetSessionList (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('EnumSession', $requestData);
			return HubSessionList::DeserializeMany($rawData);
		}

		public function GetSessionStatus (string $hubName, string $sessionName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$sessionName]],
			];

			$rawData = $this->softEther->CallMethod('GetSessionStatus', $requestData);
			return HubSessionStatus::Deserialize($rawData);
		}

		public function DisconnectSession (string $hubName, string $sessionName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$sessionName]],
			];

			$rawData = $this->softEther->CallMethod('DeleteSession', $requestData);
			return SoftEtherResult::Deserialize($rawData);
		}

		public function GetUserList (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('EnumUser', $requestData);
			return HubUserList::DeserializeMany($rawData);
		}

		public function GetUser (string $hubName, string $name) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
			];

			$rawData = $this->softEther->CallMethod('GetUser', $requestData);
			return HubUser::Deserialize($rawData);
		}

		public function GetGroupList (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('EnumGroup', $requestData);
			return HubGroupList::DeserializeMany($rawData);
		}

		public function GetGroup (string $hubName, string $name) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
			];

			$rawData = $this->softEther->CallMethod('GetGroup', $requestData);
			return HubGroup::Deserialize($rawData);
		}

		public function DeleteGroup (string $hubName, string $name) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
			];

			$rawData = $this->softEther->CallMethod('DeleteGroup', $requestData);
			return HubGroup::Deserialize($rawData);
		}

		public function Delete (string $hubName) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
			];

			$rawData = $this->softEther->CallMethod('DeleteHub', $requestData);
			return Hub::Deserialize($rawData);
		}

		public function Create (string $name, string $password, int $online, bool $noAnonymousEnumUser = true, int $hubType = HubType::Standalone, int $maxSession = 0) {
			$hashPair = $this->softEther->CreateHashAnSecure($password);

			$requestData = [
				'HubName'        => ['type' => SoftEtherValueType::String, 'value' => [$name]],
				'HashedPassword' => ['type' => SoftEtherValueType::Raw,    'value' => [$hashPair->Hash]],
				'SecurePassword' => ['type' => SoftEtherValueType::Raw,    'value' => [$hashPair->SaltedHash]],
				'Online'         => ['type' => SoftEtherValueType::Int,    'value' => [$online]],
				'MaxSession'     => ['type' => SoftEtherValueType::Int,    'value' => [$maxSession]],
				'NoEnum'         => ['type' => SoftEtherValueType::Int,    'value' => [$noAnonymousEnumUser]],
				'HubType'        => ['type' => SoftEtherValueType::Int,    'value' => [(int)$hubType]],
			];

			$rawData = $this->softEther->CallMethod('CreateHub', $requestData);
			return Hub::Deserialize($rawData);
		}

		public function CreateGroup(string $hubName, string $name, ?string $realName = null, ?string $note = null) {
			$requestData = [
				'HubName'  => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Name'     => ['type' => SoftEtherValueType::String,        'value' => [$name]],
				'Realname' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
				'Note'     => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$note]],
			];

			$rawData = $this->softEther->CallMethod('CreateGroup', $requestData);
			return HubGroup::Deserialize($rawData);
		}

		public function SetGroup2 (string $hubName, string $name, ?string $realName, ?string $note) {
			$requestData = [
				'HubName'  => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Name'     => ['type' => SoftEtherValueType::String,        'value' => [$name]],
				'Realname' => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
				'Note'     => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$note]],
			];

			$rawData = $this->softEther->CallMethod('SetGroup', $requestData);
			return HubGroup::Deserialize($rawData);
		}

		public function SetGroup (string $hubName, $group) {
			return $this->SetGroup2($hubName, $group->Name, $group->Realname, $group->Note);
		}

		public function ChangeGroupNote (string $hubName, string $name, ?string $note) {
			$group = $this->GetGroup($hubName, $name);
			$group->Note = $note;
			return $this->SetGroup($hubName, $group);
		}

		public function CreateUser (string $hubName, string $name, string $password, ?string $groupName = null, ?string $realName = null, ?string $note = null, ?DateTime $expireTime = null) {
			$hashPair = $this->softEther->CreateUserHashAndNtLm($name, $password);

			$requestData = [
				'HubName'        => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Name'           => ['type' => SoftEtherValueType::String,        'value' => [$name]],
				'GroupName'      => ['type' => SoftEtherValueType::String,        'value' => [$groupName]],
				'Realname'       => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
				'Note'           => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$note]],
				'ExpireTime'     => ['type' => SoftEtherValueType::Int64,         'value' => [SoftEtherConverter::DateTimeToSoftEtherLong($expireTime)]],
				'AuthType'       => ['type' => SoftEtherValueType::Int,           'value' => [AuthType::Password]],
				'HashedKey'      => ['type' => SoftEtherValueType::Raw,           'value' => [$hashPair->Hash]],
				'NtLmSecureHash' => ['type' => SoftEtherValueType::Raw,           'value' => [$hashPair->SaltedHash]],
			];

			$rawData = $this->softEther->CallMethod('CreateUser', $requestData);
			return HubUser::Deserialize($rawData);
		}

		public function SetUser (string $hubName, $user) {
			return $this->SetUser2($hubName, $user->Name, $user->GroupName, $user->Realname, $user->Note, $user->CreatedTime, $user->UpdatedTime, $user->ExpireTime, $user->NumLogin, $user->AuthType, $user->HashedKey, $user->NtLmSecureHash);
		}

		public function SetUser2 (string $hubName, string $name, string $groupName, string $realName, string $note, int $createTime, int $updatedTime, int $expireTime, int $numLogin, int $authType, string $hashedPw, string $securePw) {
			$requestData = [
				'HubName'        => ['type' => SoftEtherValueType::String,        'value' => [$hubName]],
				'Name'           => ['type' => SoftEtherValueType::String,        'value' => [$name]],
				'GroupName'      => ['type' => SoftEtherValueType::String,        'value' => [$groupName]],
				'Realname'       => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$realName]],
				'Note'           => ['type' => SoftEtherValueType::UnicodeString, 'value' => [$note]],
				'CreatedTime'    => ['type' => SoftEtherValueType::Int64,         'value' => [$createTime]],
				'UpdatedTime'    => ['type' => SoftEtherValueType::Int64,         'value' => [$updatedTime]],
				'ExpireTime'     => ['type' => SoftEtherValueType::Int64,         'value' => [$expireTime]],
				'NumLogin'       => ['type' => SoftEtherValueType::Int,           'value' => [$numLogin]],
				'AuthType'       => ['type' => SoftEtherValueType::Int,           'value' => [(int) $authType]],
				'HashedKey'      => ['type' => SoftEtherValueType::Raw,           'value' => [$hashedPw]],
				'NtLmSecureHash' => ['type' => SoftEtherValueType::Raw,           'value' => [$securePw]],
			];

			$rawData = $this->softEther->CallMethod('SetUser', $requestData);
			return SoftEtherModel\HubUser::Deserialize($rawData);
		}

		public function DeleteUser (string $hubName, string $name) {
			$requestData = [
				'HubName' => ['type' => SoftEtherValueType::String, 'value' => [$hubName]],
				'Name' => ['type' => SoftEtherValueType::String, 'value' => [$name]],
			];

			$rawData = $this->softEther->CallMethod('DeleteUser', $requestData);
			return HubUser::Deserialize($rawData);
		}

		public function SetUserExpireDate (string $hubName, string $name, DateTime $expireDate) {
			$user = $this->GetUser($hubName, $name);
			if ($user->NotValid()) {
				return $user;
			}

			$user->ExpireTime = SoftEtherConverter::DateTimeToSoftEtherLong($expireDate);
			return $this->SetUser($hubName, $user);
		}

		public function SetUserPassword (string $hubName, string $name, string $password) {
			$user = $this->GetUser($hubName, $name);
			if ($user->NotValid()) {
				return $user;
			}

			$hashPair = $this->softEther->CreateUserHashAndNtLm($name, $password);

			$user->HashedKey = $hashPair->Hash;
			$user->NtLmSecureHash = $hashPair->SaltedHash;

			return $this->SetUser($hubName, $user);
		}
	}

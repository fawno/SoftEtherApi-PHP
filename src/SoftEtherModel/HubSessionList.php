<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubSessionList extends BaseSoftEtherModel {
		public $BridgeMode;
		public $Client_BridgeMode;
		public $Client_MonitorMode;
		public $CurrentNumTcp;
		public $Hostname;
		public $HubName;
		public $Ip;
		public $Ip_ipv6_array;
		public $Ip_ipv6_bool;
		public $Ip_ipv6_scope_id;
		public $IsDormant;
		public $IsDormantEnabled;
		public $LastCommDormant;
		public $Layer3Mode;
		public $LinkMode;
		public $MaxNumTcp;
		public $Name;
		public $PacketNum;
		public $PacketSize;
		public $RemoteHostname;
		public $RemoteSession;
		public $SecureNATMode;
		public $UniqueId;
		public $Username;
		public $VLanId;
	}

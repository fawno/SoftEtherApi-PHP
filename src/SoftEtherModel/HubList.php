<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubList extends BaseSoftEtherModel {
		public $CreatedTime;
		public $ExRecvBroadcastBytes;
		public $ExRecvBroadcastCount;
		public $ExRecvUnicastBytes;
		public $ExRecvUnicastCount;
		public $ExSendBroadcastBytes;
		public $ExSendBroadcastCount;
		public $ExSendUnicastBytes;
		public $ExSendUnicastCount;
		public $HubName;
		public $HubType;
		public $IsTrafficFilled;
		public $LastCommTime;
		public $LastLoginTime;
		public $NumGroups;
		public $NumIpTables;
		public $NumLogin;
		public $NumMacTables;
		public $NumSessions;
		public $NumUsers;
		public $Online;
	}

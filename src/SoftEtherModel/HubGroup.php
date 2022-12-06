<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubGroup extends BaseSoftEtherModel {
		public $HubName;
		public $Name;
		public $Note;
		public $Realname;
		public $RecvBroadcastBytes;
		public $RecvBroadcastCount;
		public $RecvUnicastBytes;
		public $RecvUnicastCount;
		public $SendBroadcastBytes;
		public $SendBroadcastCount;
		public $SendUnicastBytes;
		public $SendUnicastCount;
	}

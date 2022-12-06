<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubLog extends BaseSoftEtherModel {
		public $HubName;
		public $PacketLogConfig;
		public $PacketLogSwitchType;
		public $SavePacketLog;
		public $SaveSecurityLog;
		public $SecurityLogSwitchType;
	}

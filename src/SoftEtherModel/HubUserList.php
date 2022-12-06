<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use DateTimeZone;
	use SoftEtherApi\Infrastructure\SoftEtherConverter;
	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubUserList extends BaseSoftEtherModel {
		public $AuthType;
		public $DenyAccess;
		public $Expires;
		public $ExRecvBroadcastBytes;
		public $ExRecvBroadcastCount;
		public $ExRecvUnicastBytes;
		public $ExRecvUnicastCount;
		public $ExSendBroadcastBytes;
		public $ExSendBroadcastCount;
		public $ExSendUnicastBytes;
		public $ExSendUnicastCount;
		public $GroupName;
		public $HubName;
		public $IsExpiresFilled;
		public $IsTrafficFilled;
		public $LastLoginTime;
		public $Name;
		public $Note;
		public $NumLogin;
		public $Realname;

		public function getExpires (string $format = 'Y-m-d H:i:s', ?DateTimeZone $dateTimeZone = null): ?string {
			return $this->Expires ? SoftEtherConverter::SoftEtherLongToDateTime($this->Expires, $dateTimeZone)->format($format) : null;
		}

		public function getLastLogin (string $format = 'Y-m-d H:i:s', ?DateTimeZone $dateTimeZone = null): ?string {
			return $this->LastLoginTime ? SoftEtherConverter::SoftEtherLongToDateTime($this->LastLoginTime, $dateTimeZone)->format($format) : null;
		}
	}

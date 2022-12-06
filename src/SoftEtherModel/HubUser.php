<?php
	declare(strict_types=1);

	namespace SoftEtherApi\SoftEtherModel;

	use DateTimeZone;
	use SoftEtherApi\Infrastructure\SoftEtherConverter;
	use SoftEtherApi\Model\BaseSoftEtherModel;

	class HubUser extends BaseSoftEtherModel {
		public $AuthType;
		public $CreatedTime;
		public $ExpireTime;
		public $GroupName;
		public $HashedKey;
		public $HubName;
		public $Name;
		public $Note;
		public $NtUsername;
		public $NtLmSecureHash;
		public $NumLogin;
		public $Realname;
		public $RecvBroadcastBytes;
		public $RecvBroadcastCount;
		public $RecvUnicastBytes;
		public $RecvUnicastCount;
		public $SendBroadcastBytes;
		public $SendBroadcastCount;
		public $SendUnicastBytes;
		public $SendUnicastCount;
		public $UpdatedTime;

		public function getCreated (string $format = 'Y-m-d H:i:s', ?DateTimeZone $dateTimeZone = null): ?string {
			return $this->CreatedTime ? SoftEtherConverter::SoftEtherLongToDateTime($this->CreatedTime, $dateTimeZone)->format($format) : null;
		}

		public function getExpire (string $format = 'Y-m-d H:i:s', ?DateTimeZone $dateTimeZone = null): ?string {
			return $this->ExpireTime ? SoftEtherConverter::SoftEtherLongToDateTime($this->ExpireTime, $dateTimeZone)->format($format) : null;
		}

		public function getUpdated (string $format = 'Y-m-d H:i:s', ?DateTimeZone $dateTimeZone = null): ?string {
			return $this->UpdatedTime ? SoftEtherConverter::SoftEtherLongToDateTime($this->UpdatedTime, $dateTimeZone)->format($format) : null;
		}
	}

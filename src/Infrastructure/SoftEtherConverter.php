<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Infrastructure;

	use DateInterval;
	use DateTime;
	use DateTimeZone;

	class SoftEtherConverter {
		public static function DateTimeToSoftEtherLong (?DateTime $date = null) : ?int {
			if ($date == null) {
				return null;
			}

			//sub 9 Hours from UTC for JAPAN Timezone which SoftEther expects
			$date->sub(new DateInterval('PT9H'));
			return $date->getTimestamp() * 1000;
		}

		public static function SoftEtherLongToDateTime (?int $date = null, ?DateTimeZone $dateTimeZone = null) : ?DateTime {
			if ($date == null) {
				return null;
			}

			$result = new DateTime();
			$result->setTimestamp((int) ($date / 1000));
			//add 9 Hours for JAPAN Timezone which SoftEther delivers to UTC
			$result->add(new DateInterval('PT9H'));

			if ($dateTimeZone) {
				$result->setTimezone($dateTimeZone);
			}

			return $result;
		}
	}

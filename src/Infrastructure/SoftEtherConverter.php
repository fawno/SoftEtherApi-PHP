<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Infrastructure;

	use DateInterval;
	use DateTime;
	use DateTimeZone;

	class SoftEtherConverter {
		public static function DateTimeToSoftEtherLong(DateTime $date = null): ?int {
			if ($date == null) {
				return null;
			}

			$softEtherDate = $date;
			//sub 9 Hours from UTC for JAPAN Timezone which SoftEther expects
			$softEtherDate->sub(new DateInterval("PT9H"));
			return $softEtherDate->getTimestamp() * 1000;
		}

		public static function SoftEtherLongToDateTime(?int $date = null, ?DateTimeZone $dateTimeZone = null): ?DateTime {
			if ($date == null) {
				return null;
			}

			$result = new DateTime();
			$result->setTimestamp($date / 1000);
			$result->add(new DateInterval("PT9H"));//add 9 Hours for JAPAN Timezone which SoftEther delivers to UTC

			if ($dateTimeZone) {
				$result->setTimezone($dateTimeZone);
			}

			return $result;
		}
	}
<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Infrastructure;

	use SoftEtherApi\Containers;
use SoftEtherApi\Containers\SoftEtherError;
use SoftEtherApi\Containers\SoftEtherList;

	class ModelDeserializer {
		public static function FilterKeyName (string $val) : string {
			$replace = [
				'.' => '',
				'@' => '_',
				':' => '_'
			];

			return trim(str_replace(array_keys($replace), array_values($replace), $val));
		}

		private static function CreateKeyFieldTuple (string $val) : array {
			return ['key' => $val, 'keyName' => strtolower(self::FilterKeyName($val))];
		}

		public static function Deserialize (string $class, array $keyValArray) : SoftEtherList {
			$rawKeys = array_keys($keyValArray);
			$keys = [];
			foreach ($rawKeys as &$el) {
				$keys[strtolower(self::FilterKeyName($el))] = $el;
			}

			$returnVal = new SoftEtherList();
			$elementCount = max(array_map('count', array_column($keyValArray, 'value')));

			if ($elementCount <= 1 and in_array('error', $keys)) {
				$returnVal->Error = SoftEtherError::ErrorList[$keyValArray['error']['value'][0]];
				return $returnVal;
			}

			$valFields = array_keys(get_class_vars($class));

			for ($i = 0; $i < $elementCount; $i++) {
				$returnVal[] = new $class();
				foreach ($valFields as $field) {
					$keyName = strtolower($field);
					if (!key_exists($keyName, $keys)) {
						continue;
					}

					$val = $keyValArray[$keys[$keyName]]['value'];
					$valIndex = count($val) > $i ? $i : 0;
					$returnVal[$i]->$field = $val[$valIndex]; //determine if it is an array!
				}
			}

			return $returnVal;
		}
	}

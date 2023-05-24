<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Containers;

	use SoftEtherApi\SoftEtherException;

	class SoftEtherProtocol {
		public static function SerializeInt (int $val) : string {
			return pack('N', $val);
		}

		public static function SerializeLong (int $val) : string {
			if (PHP_INT_SIZE > 4) {
				return pack('J', $val);
			}

			//For 32Bit php

			$lowerVal = (int) $val;
			$toRemoveFromUpper = $lowerVal >= 0 ? $lowerVal : ($lowerVal & 0x7FFFFFFF) + 0x80000000;
			$upperVal = ($val - $toRemoveFromUpper) / 0x100000000;

			$upperRetVal = pack('N', $upperVal);
			$LowerRetVal = pack('N', $lowerVal);
			return $upperRetVal . $LowerRetVal;
		}

		private static function SerializeBytes (array &$val) : string {
			return pack('C*', ...$val);
		}

		public static function DeserializeInt (string &$val) : int {
			return unpack('N', $val)[1];
		}

		public static function Serialize ($model) : string {
			$returnVal = '';
			$returnVal .= self::SerializeInt(count($model));

			foreach ($model as $key => $val) {
				$keyLen = strlen($key);

				$returnVal .= self::SerializeInt($keyLen + 1);
				$returnVal .= $key;

				$returnVal .= self::SerializeInt($val['type']);
				$returnVal .= self::SerializeInt(count($val['value']));

				switch ($val['type']) {
					case SoftEtherValueType::Int:
						foreach ($val['value'] as $t) {
							$returnVal .= self::SerializeInt((int) $t);
						}
						break;
					case SoftEtherValueType::Raw:
						foreach ($val['value'] as $t) {
							$returnVal .= self::SerializeInt(count($t));
							$returnVal .= self::SerializeBytes($t);
						}
						break;
					case SoftEtherValueType::String:
						foreach ($val['value'] as $t) {
							$returnVal .= self::SerializeInt(strlen((string) $t));
							$returnVal .= $t;
						}
						break;
					case SoftEtherValueType::UnicodeString:
						foreach ($val['value'] as $t) {
							$returnVal .= self::SerializeInt(strlen((string) $t));
							$returnVal .= $t;
						}
						break;
					case SoftEtherValueType::Int64:
						foreach ($val['value'] as $t) {
							$returnVal .= self::SerializeLong((int) $t);
						}
						break;
					default:
						throw new SoftEtherException('ValueType is not valid');
				}
			}

			return $returnVal;
		}

		private static function readInt (string &$val, int &$index) : int {
			$retValArr = unpack('N', $val, $index);
			$index += 4;
			return (int) $retValArr[1];
		}

		public static function readLong (string &$val, int &$index) : int {
			//For 64Bit php
			if (PHP_INT_SIZE > 4) {
				$retValArr = unpack('J', $val, $index);
				$index += 8;
				return $retValArr[1];
			}

			//For 32Bit php
			$upperVal = unpack('N', $val, $index)[1];
			$lowerVal = unpack('N', $val, $index + 4)[1];

			$upperVal = $upperVal >= 0 ? $upperVal : (float)($upperVal & 0x7FFFFFFF) + 0x80000000;
			$lowerVal = $lowerVal >= 0 ? $lowerVal : (float)($lowerVal & 0x7FFFFFFF) + 0x80000000;

			$retVal = ($upperVal * 0x100000000) + $lowerVal;

			$index += 8;
			return $retVal;
		}

		private static function readString (string &$val, int &$index, int $size) : string {
			$retVal = substr($val, $index, $size);
			$index += $size;
			return $retVal;
		}

		private static function readBytes (string &$val, int &$index, int $size) : array {
			$retVal = unpack('C*', substr($val, $index, $size));
			$index += $size;
			return $retVal;
		}

		public static function Deserialize (string &$val) : array {
			$index = 0;
			$count = self::readInt($val, $index);

			$res = [];
			for ($i = 0; $i < $count; $i++) {
				$keyLen = self::readInt($val, $index);
				$key = self::readString($val, $index, $keyLen - 1);
				$valueType = self::readInt($val, $index);
				$valueCount =  self::readInt($val, $index);

				$list = [];
				for ($j = 0; $j < $valueCount; $j++) {
					switch ($valueType) {
						case SoftEtherValueType::Int:
							array_push($list, self::readInt($val, $index));
							break;
						case SoftEtherValueType::Raw:
							$strLen = self::readInt($val, $index);
							array_push($list, self::readBytes($val, $index, $strLen));
							break;
						case SoftEtherValueType::String:
							$strLen = self::readInt($val, $index);
							array_push($list, self::readString($val, $index, $strLen));
							break;
						case SoftEtherValueType::UnicodeString:
							$strLen = self::readInt($val, $index);
							//softether adds a additional 0-byte to every string. For future sake, just trim it instead of reading a byte less
							array_push($list, rtrim(self::readString($val, $index, $strLen), "\0"));
							break;
						case SoftEtherValueType::Int64:
							array_push($list, self::readLong($val, $index));
							break;
						default:
							throw new SoftEtherException('ValueType is not valid');
					}
				}

				$res[$key] = ['type' => $valueType, 'value' => $list];
			}
			return $res;
		}
	}

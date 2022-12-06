<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Infrastructure;

	use SoftEtherApi\Infrastructure\SHA0Context;

	class SHA0 {
		private $_context = null;

		public function __construct () {
			$this->_context = new SHA0Context();
			$this->_context->H = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476, 0xc3d2e1f0];
			$this->_context->Length = 0;
		}

		public static function ArrayCopyInto (array &$source, array &$dest, int $length, int $sourceIndex = 0, int $destIndex = 0) {
			for ($i = 0; $i < $length; ++$i) {
				$dest[$destIndex + $i] = $source[$sourceIndex + $i];
			}
		}

		public static function ArrayClear (array &$source, int $startIndex, int $length) {
			for ($i = 0; $i < $length; ++$i) {
				$source[$startIndex + $i] = 0;
			}
		}

		public function Update (array $data) {
			$data = array_merge($data); //reset values to 0 based array
			$dataLength = count($data);

			if ($dataLength >= 64 - $this->_context->Length % 64) {
				self::ArrayCopyInto($data, $this->_context->Buffer, 64 - $this->_context->Length % 64, 0, $this->_context->Length % 64);

				$dataStartIndex = 64 - $this->_context->Length % 64;
				$dataLength -= 64 - $this->_context->Length % 64;

				self::Transform($this->_context, $this->_context->Buffer, 0, 1);
				self::Transform($this->_context, $data, $dataStartIndex, $dataLength / 64);

				$dataStartIndex += $dataLength & ~63;
				$dataLength %= 64;

				self::ArrayCopyInto($data, $this->_context->Buffer, $dataLength, $dataStartIndex, 0);
			} else {
				self::ArrayCopyInto($data, $this->_context->Buffer, $dataLength, 0, $this->_context->Length % 64);
			}

			$this->_context->Length += count($data);

			return $this;
		}

		public function Digest () : array {
			$digest = array_fill(0, 20, 0);
			$tmpContext = new Sha0Context();

			self::ArrayCopyInto($this->_context->H, $tmpContext->H, count($tmpContext->H));
			self::ArrayCopyInto($this->_context->Buffer, $tmpContext->Buffer, $this->_context->Length % 64);

			$tmpContext->Buffer[$this->_context->Length % 64] = 0x80;

			if ($this->_context->Length % 64 < 56) {
				self::ArrayClear($tmpContext->Buffer, $this->_context->Length % 64 + 1, 55 - $this->_context->Length % 64);
			} else {
				self::ArrayClear($tmpContext->Buffer, $this->_context->Length % 64 + 1, 63 - $this->_context->Length % 64);

				self::Transform($tmpContext, $tmpContext->Buffer, 0, 1);
				self::ArrayClear($tmpContext->Buffer, 0, 56);
			}

			self::UNPACK_64_BE($this->_context->Length * 8, $tmpContext->Buffer, 56);

			self::Transform($tmpContext, $tmpContext->Buffer, 0, 1);

			self::UNPACK_32_BE($tmpContext->H[0], $digest, 0);
			self::UNPACK_32_BE($tmpContext->H[1], $digest, 4);
			self::UNPACK_32_BE($tmpContext->H[2], $digest, 8);
			self::UNPACK_32_BE($tmpContext->H[3], $digest, 12);
			self::UNPACK_32_BE($tmpContext->H[4], $digest, 16);
			return $digest;
		}

		private static function Transform (SHA0Context &$context, array &$data, int $dataStartIndex, int $blocks) : void {
			for ($i = 0; $i < $blocks; ++$i) {
				$wv = array_fill(0, 5, 0);
				$w = array_fill(0, 16, 0);

				self::PACK_32_BE($data, $dataStartIndex + ($i << 6), $w[0]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 4, $w[1]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 8, $w[2]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 12, $w[3]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 16, $w[4]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 20, $w[5]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 24, $w[6]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 28, $w[7]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 32, $w[8]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 36, $w[9]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 40, $w[10]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 44, $w[11]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 48, $w[12]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 52, $w[13]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 56, $w[14]);
				self::PACK_32_BE($data, $dataStartIndex + ($i << 6) + 60, $w[15]);

				$wv[0] = $context->H[0];
				$wv[1] = $context->H[1];
				$wv[2] = $context->H[2];
				$wv[3] = $context->H[3];
				$wv[4] = $context->H[4];

				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, $w[0], 1);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, $w[1], 1);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, $w[2], 1);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, $w[3], 1);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, $w[4], 1);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, $w[5], 1);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, $w[6], 1);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, $w[7], 1);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, $w[8], 1);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, $w[9], 1);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, $w[10], 1);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, $w[11], 1);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, $w[12], 1);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, $w[13], 1);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, $w[14], 1);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, $w[15], 1);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 0), 1);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 1), 1);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 2), 1);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 3), 1);

				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 4), 2);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 5), 2);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 6), 2);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 7), 2);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 8), 2);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 9), 2);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 10), 2);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 11), 2);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 12), 2);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 13), 2);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 14), 2);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 15), 2);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 0), 2);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 1), 2);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 2), 2);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 3), 2);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 4), 2);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 5), 2);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 6), 2);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 7), 2);

				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 8), 3);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 9), 3);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 10), 3);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 11), 3);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 12), 3);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 13), 3);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 14), 3);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 15), 3);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 0), 3);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 1), 3);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 2), 3);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 3), 3);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 4), 3);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 5), 3);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 6), 3);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 7), 3);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 8), 3);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 9), 3);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 10), 3);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 11), 3);

				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 12), 4);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 13), 4);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 14), 4);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 15), 4);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 0), 4);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 1), 4);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 2), 4);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 3), 4);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 4), 4);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 5), 4);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 6), 4);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 7), 4);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 8), 4);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 9), 4);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 10), 4);
				self::SHA0_PRC($wv, 0, 1, 2, 3, 4, self::SHA0_EXT($w, 11), 4);
				self::SHA0_PRC($wv, 4, 0, 1, 2, 3, self::SHA0_EXT($w, 12), 4);
				self::SHA0_PRC($wv, 3, 4, 0, 1, 2, self::SHA0_EXT($w, 13), 4);
				self::SHA0_PRC($wv, 2, 3, 4, 0, 1, self::SHA0_EXT($w, 14), 4);
				self::SHA0_PRC($wv, 1, 2, 3, 4, 0, self::SHA0_EXT($w, 15), 4);

				$context->H[0] = self::To32BitInteger($context->H[0] + $wv[0]);
				$context->H[1] = self::To32BitInteger($context->H[1] + $wv[1]);
				$context->H[2] = self::To32BitInteger($context->H[2] + $wv[2]);
				$context->H[3] = self::To32BitInteger($context->H[3] + $wv[3]);
				$context->H[4] = self::To32BitInteger($context->H[4] + $wv[4]);
			}
		}

		private static function SHA0_R1 (int $x, int $y, int $z) : int {
			return self::To32BitInteger(($z ^ ($x & ($y ^ $z))) + 0x5a827999);
		}

		private static function SHA0_R2 (int $x, int $y, int $z) : int {
			return self::To32BitInteger(($x ^ $y ^ $z) + 0x6ed9eba1);
		}

		private static function SHA0_R3 (int $x, int $y, int $z) : int {
			return self::To32BitInteger((($x & $y) | ($z & ($x | $y))) + 0x8f1bbcdc);
		}

		private static function SHA0_R4 (int $x, int $y, int $z) : int {
			return self::To32BitInteger(($x ^ $y ^ $z) + 0xca62c1d6);
		}

		private static function SHA0_PRC (array &$wv, int $a, int $b, int $c, int $d, int $e, int $idx, int $rnd) : void {
			$val = 0;
			switch ($rnd) {
				case 1:
					$val = self::SHA0_R1($wv[$b], $wv[$c], $wv[$d]);
					break;
				case 2:
					$val = self::SHA0_R2($wv[$b], $wv[$c], $wv[$d]);
					break;
				case 3:
					$val = self::SHA0_R3($wv[$b], $wv[$c], $wv[$d]);
					break;
				case 4:
					$val = self::SHA0_R4($wv[$b], $wv[$c], $wv[$d]);
					break;
			}


			$wv[$e] = self::To32BitInteger($wv[$e] + self::ROR($wv[$a], 27) + $val + $idx);
			$wv[$b] = self::ROR($wv[$b], 2);
		}

		private static function SHA0_EXT (array &$w, int $i) : int {
			$w[$i] = self::To32BitInteger($w[$i] ^ ($w[($i - 3) & 0x0F] ^ $w[($i - 8) & 0x0F] ^ $w[($i - 14) & 0x0F]));
			return $w[$i];
		}

		private static function ROR (int $x, int $y) : int {
			return self::To32BitInteger(self::RightLogicalShift($x, $y) ^ ($x << (4 * 8 - $y))); //4 = sizeof(uint)
		}

		private static function PACK_32_BE (array &$buf, int $startIndex, int &$x) : void {
			$x = self::To32BitInteger(($buf[$startIndex] << 24) ^ ($buf[$startIndex + 1] << 16) ^ ($buf[$startIndex + 2] << 8) ^ $buf[$startIndex + 3]);
		}

		private static function UNPACK_32_BE (int $x, array &$buf, int $startIndex) : void {
			$buf[$startIndex] = self::ToByte(self::RightLogicalShift($x, 24));
			$buf[$startIndex + 1] = self::ToByte(self::RightLogicalShift($x, 16));
			$buf[$startIndex + 2] = self::ToByte(self::RightLogicalShift($x, 8));
			$buf[$startIndex + 3] = self::ToByte($x);
		}

		private static function UNPACK_64_BE (int $x, array &$buf, int $startIndex) : void {
			$buf[$startIndex] = self::ToByte(self::RightLogicalShift($x, 56));
			$buf[$startIndex + 1] = self::ToByte(self::RightLogicalShift($x, 48));
			$buf[$startIndex + 2] = self::ToByte(self::RightLogicalShift($x, 40));
			$buf[$startIndex + 3] = self::ToByte(self::RightLogicalShift($x, 32));
			$buf[$startIndex + 4] = self::ToByte(self::RightLogicalShift($x, 24));
			$buf[$startIndex + 5] = self::ToByte(self::RightLogicalShift($x, 16));
			$buf[$startIndex + 6] = self::ToByte(self::RightLogicalShift($x, 8));
			$buf[$startIndex + 7] = self::ToByte($x);
		}

		private static function To32BitInteger (int $val) : int {
			return $val & 0xFFFFFFFF;
		}

		private static function ToByte (int $val) : int {
			return $val & 0xFF;
		}

		private static function RightLogicalShift (int $val, int $toShift) : int {
			if ($toShift <= 0) {
				return $val;
			}

			if ((int) $val >= 0) {
				return $val >> $toShift;
			}

			$validMask = ~((int)0x80000000 >> ($toShift - 1));
			$ret = (($val >> $toShift) & $validMask);

			return $ret;
		}
	}

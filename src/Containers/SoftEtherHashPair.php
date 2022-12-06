<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Containers;
	class SoftEtherHashPair {
		public $Hash;
		public $SaltedHash;

		public function __construct ($hash, $saltedHash) {
			$this->Hash = $hash;
			$this->SaltedHash = $saltedHash;
		}
	}

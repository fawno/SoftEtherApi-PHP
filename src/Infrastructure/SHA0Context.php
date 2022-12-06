<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Infrastructure;

	class SHA0Context {
		public $Buffer;
		public $H;
		public $Length;

		public function __construct () {
			$this->Buffer = array_fill(0, 64, 0);
			$this->H = array_fill(0, 5, 0);
			$this->Length = 0;
		}
	}

<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Containers;
	use ArrayObject;
	use ArrayIterator;
	use SoftEtherApi\Containers\SoftEtherError;

	class SoftEtherList extends ArrayObject {
		public $Error;

		public function __construct($input = [], int $flags = 0, string $iterator_class = ArrayIterator::class) {
			parent::__construct($input, $flags, $iterator_class);
			$this->Error = SoftEtherError::NoError;
		}

		public function Valid () : bool {
			return $this->Error == SoftEtherError::NoError;
		}

		public function NotValid() : bool {
			return !$this->Valid();
		}
	}

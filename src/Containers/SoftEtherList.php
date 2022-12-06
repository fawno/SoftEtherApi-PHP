<?php


namespace SoftEtherApi\Containers
{
    use ArrayObject;
    use ArrayIterator;
    use SoftEtherApi\Containers;

    class SoftEtherList extends ArrayObject
    {
        public $Error;

        public function __construct($input = [], int $flags = 0, string $iterator_class = ArrayIterator::class)
        {
            parent::__construct($input, $flags, $iterator_class);
            $this->Error = Containers\SoftEtherError::NoError;
        }

        public function Valid()
        {
            return $this->Error == Containers\SoftEtherError::NoError;
        }

        public function NotValid()
        {
            return !$this->Valid();
        }
    }
}
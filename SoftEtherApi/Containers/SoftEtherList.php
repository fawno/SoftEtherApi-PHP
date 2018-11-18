<?php


namespace SoftEtherApi\Containers
{
    use SoftEtherApi\Containers;

    class SoftEtherList extends \ArrayObject
    {
        public $Error;

        public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
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
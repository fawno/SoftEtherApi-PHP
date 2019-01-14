<?php

namespace SoftEtherApi\Model
{
    use SoftEtherApi\Infrastructure;
    use SoftEtherApi\Containers;

    abstract class BaseSoftEtherModel
    {
        public $Error;

        public function __construct($error = Containers\SoftEtherError::NoError)
        {
            $this->Error = $error;
        }

        public function Valid()
        {
            return $this->Error == Containers\SoftEtherError::NoError;
        }
        
        public function NotValid()
        {
            return !$this->Valid();
        }

        public static function Deserialize($collection)
        {
            $result = Infrastructure\ModelDeserializer::Deserialize(static::class, $collection);
            if($result->NotValid())
                return $result;
            return $result[0];
        }

        public static function DeserializeMany($collection)
        {
            return Infrastructure\ModelDeserializer::Deserialize(static::class, $collection);
        }
    }
}
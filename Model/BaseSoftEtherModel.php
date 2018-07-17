<?php

namespace SoftEtherApi\Model
{
    require_once('Infrastructure/ModelDeserializer.php');
    require_once('Containers/SoftEtherError.php');

    use SoftEtherApi\Infrastructure\ModelDeserializer;
    use SoftEtherApi\Containers\SoftEtherError;

    abstract class BaseSoftEtherModel
    {
        public $Error;

        public function __construct($error = SoftEtherError::NoError)
        {
            $this->Error = $error;
        }

        public function Valid()
        {
            return $this->Error == SoftEtherError::NoError;
        }
        
        public function NotValid()
        {
            return !$this->Valid();
        }

        public static function Deserialize($collection)
        {
            return ModelDeserializer::Deserialize(static::class, $collection);
        }

        public static function DeserializeMany($collection, $moreThanOne = true)
        {
            return ModelDeserializer::DeserializeMany(static::class, $collection, $moreThanOne);
        }
    }
}
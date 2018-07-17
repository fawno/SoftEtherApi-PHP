<?php

namespace SoftEtherApi\Infrastructure
{
    require_once('Containers/SoftEtherError.php');
    use SoftEtherApi\Containers\SoftEtherError;

    class ModelDeserializer
    {
        public static function FilterKeyName($val)
        {
            $replace = [
                '.' => '',
                '@' => '_',
                ':' => '_'
            ];

            return trim(str_replace(array_keys($replace), array_values($replace), $val));
        }

        private static function CreateKeyFieldTuple($val)
        {
            return ['key' => $val, 'keyName' => strtolower(self::FilterKeyName($val))];
        }

        public static function Deserialize($class, $keyValArray)
        {
            $rawKeys = array_keys($keyValArray);
            $keys = [];
            foreach($rawKeys as &$el)
            {
                $keys[strtolower(self::FilterKeyName($el))] = $el;
            }

            $returnVal = new $class();
            $valFields = array_keys(get_class_vars($class));

            foreach ($valFields as $field)
            {
                $keyName = strtolower($field);
                if (!key_exists($keyName, $keys))
                    continue;

                $val = $keyValArray[$keys[$keyName]]['value'];
                $returnVal->$field = $val; //determine if it is an array!
            }

            if(in_array('error', $keys))
                $returnVal->Error = SoftEtherError::ErrorList[$returnVal->Error[0]];

            return $returnVal;
        }
    }
}
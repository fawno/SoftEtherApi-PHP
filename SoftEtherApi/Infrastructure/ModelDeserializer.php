<?php

namespace SoftEtherApi\Infrastructure
{
    use SoftEtherApi\Containers;

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
            foreach ($rawKeys as &$el) {
                $keys[strtolower(self::FilterKeyName($el))] = $el;
            }

            $returnVal = new $class();
            $valFields = array_keys(get_class_vars($class));

            foreach ($valFields as $field) {
                $keyName = strtolower($field);
                if (!key_exists($keyName, $keys))
                    continue;

                $val = $keyValArray[$keys[$keyName]]['value'];
                $returnVal->$field = $val; //determine if it is an array!
            }

            if (in_array('error', $keys))
                $returnVal->Error = Containers\SoftEtherError::ErrorList[$returnVal->Error[0]];

            return $returnVal;
        }

        public static function DeserializeMany($class, array $keyValArray)
        {
            $rawKeys = array_keys($keyValArray);
            $keys = [];
            foreach ($rawKeys as &$el) {
                $keys[strtolower(self::FilterKeyName($el))] = $el;
            }

            $returnVal = new Containers\SoftEtherList();
            $elementCount = max(array_map('count', array_column($keyValArray, 'value')));

            if ($elementCount <= 1 && in_array('error', $keys)) {
                $returnVal->Error = Containers\SoftEtherError::ErrorList[$keyValArray['error']['value'][0]];
                return $returnVal;
            }

            $valFields = array_keys(get_class_vars($class));

            for ($i = 0; $i < $elementCount; $i++) {
                $returnVal[] = new $class();
                foreach ($valFields as $field) {
                    $keyName = strtolower($field);
                    if (!key_exists($keyName, $keys))
                        continue;

                    $val = $keyValArray[$keys[$keyName]]['value'];
                    $returnVal[$i]->$field = $val[$i]; //determine if it is an array!
                }
            }

            return $returnVal;
        }
    }
}
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

        public static function Deserialize($class, array $keyValArray)
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
                    $valIndex = count($val) > $i ? $i : 0;
                    $returnVal[$i]->$field = $val[$valIndex]; //determine if it is an array!
                }
            }

            return $returnVal;
        }
    }
}
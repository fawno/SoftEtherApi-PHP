<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Model;

	use SoftEtherApi\Containers\SoftEtherError;
	use SoftEtherApi\Containers\SoftEtherList;
	use SoftEtherApi\Infrastructure\ModelDeserializer;

	abstract class BaseSoftEtherModel {
		public $Error;

		public function __construct (string $error = SoftEtherError::NoError) {
			$this->Error = $error;
		}

		public function Valid () : bool {
			return $this->Error == SoftEtherError::NoError;
		}

		public function NotValid () : bool {
			return !$this->Valid();
		}

		public static function Deserialize (array $collection) {
			$result = ModelDeserializer::Deserialize(static::class, $collection);

			return $result->NotValid() ? $result : $result[0];
		}

		public static function DeserializeMany (array $collection) : SoftEtherList {
			return ModelDeserializer::Deserialize(static::class, $collection);
		}
	}

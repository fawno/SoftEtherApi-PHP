<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Model;
	class SoftEtherHttpResult {
		public $code;
		public $headers;
		public $length;
		public $body;

		public function  __construct (?int $code = null, array $headers = [], ?int $lenght = null, ?string $body = null) {
			$this->code = $code;
			$this->headers = $headers;
			$this->lenght = $lenght;
			$this->body = $body;
		}
	}

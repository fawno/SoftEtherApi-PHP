<?php
	declare(strict_types=1);

	namespace SoftEtherApi;

	use Exception;
	use Throwable;

	class SoftEtherException extends Exception {
    public const ERROR_INDETERMINATE = 0;

		public function __construct (string $message = '', int $code = self::ERROR_INDETERMINATE, int $severity = E_ERROR, ?string $filename = null, ?int $line = null , ?Throwable $previous = null) {
			$this->severity = $severity;

			if (!is_null($filename)) {
				$this->filename = $filename;
			}

			if (!is_null($line)) {
				$this->line = $line;
			}

			parent::__construct($message, $code, $previous);
		}

		public function getSeverity () : int {
			return $this->severity;
		}
	}

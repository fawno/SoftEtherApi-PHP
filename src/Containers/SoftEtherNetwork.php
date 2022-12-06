<?php
	declare(strict_types=1);

	namespace SoftEtherApi\Containers;

	use SoftEtherApi\Model\SoftEtherHttpResult;

	class SoftEtherNetwork {
		public static function GetDefaultHeaders () : array {
			return [
				'Keep-Alive' => 'timeout=15; max=19',
				'Connection' => 'Keep-Alive',
				'Content-Type' => 'application/octet-stream',
			];
		}

		public static function SendHttpRequest ($socket, string $method, string $target, string $body, array $headers) : void {
			$header = strtoupper($method) . " {$target} HTTP/1.1\r\n";

			foreach ($headers as $key => $val) {
				$header .= "${key}: ${val}\r\n";
			}

			if (!array_key_exists('Content-Length', $headers)) {
				$header .= 'Content-Length: ' . strlen($body) . "\r\n";
			}

			$header .= "\r\n";
			$bytesWritten = fwrite($socket, $header);
			$bytesWritten = fwrite($socket, $body);
			$flushDone = fflush($socket);
		}

		public static function GetHttpResponse ($socket) : SoftEtherHttpResult {
			$firstLine = fgets($socket);
			$responseCode = (int) substr($firstLine, 9, 3);
			$responseHeaders = [];
			$responseLength = 0;

			do {
				$headerLine = fgets($socket);

				if ($headerLine != "\r\n") {
					$headerArray = explode(': ', $headerLine);
					$headerName = strtolower(trim($headerArray[0]));
					$headerValue = trim($headerArray[1]);

					$responseHeaders[$headerName] = $headerValue;

					if ($headerName == "content-length") {
						$responseLength = (int) $headerValue;
					}
				}
			} while ($headerLine != "\r\n");

			$responseBody = '';
			while (strlen($responseBody) < $responseLength) {
				$responseBody .= fread($socket, $responseLength - strlen($responseBody));
			}

			return new SoftEtherHttpResult($responseCode, $responseHeaders, $responseLength, $responseBody);
		}
	}

<?php

namespace Mattjgagnon\CurlService;

use RuntimeException;

final class CurlRetryException extends RuntimeException
{
    public function __construct(string $url, int $statusCode, string $responseBody, int $maxAttempts)
    {
        $message = sprintf(
            "Failed to fetch data from %s after %d attempts. Response code: %d. Response body: %s",
            $url,
            $maxAttempts,
            $statusCode,
            $responseBody
        );
        parent::__construct($message);
    }
}

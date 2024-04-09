<?php

namespace Mattjgagnon\CurlService;

use RuntimeException;

final class CurlRetryException extends RuntimeException
{
    private const MSG_FORMAT = "Failed to fetch data from %s after %d attempts. Response code: %d. Response body: %s";

    public function __construct(string $url, int $statusCode, string $responseBody, int $maxAttempts)
    {
        $message = sprintf(
            self::MSG_FORMAT,
            $url,
            $maxAttempts,
            $statusCode,
            $responseBody
        );
        parent::__construct($message);
    }
}

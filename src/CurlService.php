<?php

namespace Mattjgagnon\CurlService;

use CurlHandle;
use InvalidArgumentException;

final class CurlService
{
    private readonly CurlHandle $curlHandle;

    public function __construct(
        public string $url = '',
        private int $maxAttempts = 3,
        private int $initialBackoff = 3
    ) {
        $this->init();
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
    }

    public function close(): void
    {
        curl_close($this->curlHandle);
    }

    public function delete(): string
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->retry();
    }

    public function get(): string
    {
        $this->setOption(CURLOPT_HTTPGET, true);
        return $this->retry();
    }

    public function getErrNo(): int
    {
        return curl_errno($this->curlHandle);
    }

    public function getError(): string
    {
        return curl_error($this->curlHandle);
    }

    public function getInfo(?int $option = null): mixed
    {
        return curl_getinfo($this->curlHandle, $option);
    }

    public function head(): string
    {
        $this->setOption(CURLOPT_NOBODY, true);
        return $this->retry();
    }

    public function options(): string
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        return $this->retry();
    }

    public function post(string $payload): string
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $payload);
        return $this->retry();
    }

    public function put(string $payload): string
    {
        $this->setOption(CURLOPT_PUT, true);
        $this->setOption(CURLOPT_POSTFIELDS, $payload);
        return $this->retry();
    }

    public function setInitialBackoff(int $initialBackoff): void
    {
        if ($initialBackoff < 1) {
            throw new InvalidArgumentException('Initial backoff must be a positive number');
        }

        $this->initialBackoff = $initialBackoff;
    }

    public function setOption(int $option, mixed $value): void
    {
        curl_setopt($this->curlHandle, $option, $value);
    }

    public function setOptions(array $options): bool
    {
        return curl_setopt_array($this->curlHandle, $options);
    }

    public function setMaxAttempts(int $attempts): void
    {
        if ($attempts < 1) {
            throw new InvalidArgumentException('Maximum number of attempts must be a positive number');
        }

        $this->maxAttempts = $attempts;
    }

    public function setUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL');
        }

        $this->url = $url;
    }

    private function execute(): string
    {
        $results = curl_exec($this->curlHandle);

        if ($responseJson = json_encode($results)) {
            $response = $responseJson;
        }

        return $response ?? '';
    }

    private function init(): void
    {
        if (!empty($this->url)) {
            $this->curlHandle = curl_init($this->url);
        } else {
            $this->curlHandle = curl_init();
        }
    }

    private function retry(): string
    {
        $attempt = 0;

        while ($attempt < $this->maxAttempts) {
            $response = $this->execute();
            $statusCode = $this->getInfo(CURLINFO_HTTP_CODE);
            $this->close();

            if ($statusCode < 400) {
                return $response;
            }

            $backoff = $this->initialBackoff * (2 ** $attempt);
            usleep($backoff * 1000);
            $attempt++;
        }

        return '';
    }
}

<?php

namespace Mattjgagnon\CurlService;

use CurlHandle;

final class CurlService
{
    private readonly CurlHandle $curlHandle;

    public function __construct(public string $url = '')
    {
        $this->init();
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
    }

    public function close(): void
    {
        curl_close($this->curlHandle);
    }

    public function get(): string
    {
        $this->setOption(CURLOPT_HTTPGET, true);
        return $this->execute();
    }

    public function post(string $payload): string
    {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $payload);
        return $this->execute();
    }

    public function setOption(int $option, mixed $value): void
    {
        curl_setopt($this->curlHandle, $option, $value);
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
}
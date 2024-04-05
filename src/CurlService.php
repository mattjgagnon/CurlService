<?php

namespace Mattjgagnon\CurlService;

use CurlHandle;

final class CurlService
{
    private readonly CurlHandle $curlHandle;

    public function __construct(public string $url = '')
    {
        $this->init();
    }

    public function get(): string
    {
        $response = curl_exec($this->curlHandle);

        if ($responseJson = json_encode($response)) {
            return $responseJson;
        }

        return '';
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

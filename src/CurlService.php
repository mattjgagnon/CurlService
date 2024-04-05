<?php

namespace Mattjgagnon\CurlService;

final class CurlService
{
    public function __construct(public string $url = '')
    {
        if (!empty($this->url)) {
            curl_init($this->url);
        } else {
            curl_init();
        }
    }

    public function get()
    {

    }
}

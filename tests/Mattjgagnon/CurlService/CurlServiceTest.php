<?php

namespace Mattjgagnon\CurlService;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlServiceTest extends TestCase
{
    #[Test] public function it_makes_a_get_request()
    {
        // assemble
        $url = 'https://example.org';
        $curl = new CurlService($url);

        // act
        $response = $curl->get();

        // assert
        $this->assertIsString($response);
    }
}

<?php

namespace Mattjgagnon\CurlService;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlServiceTest extends TestCase
{
    #[DataProvider('urlProvider')] #[Test] public function it_makes_a_get_request($url)
    {
        // assemble
        $curl = new CurlService($url);

        // act
        $response = $curl->get();

        // assert
        $this->assertIsString($response);
    }

    #[DataProvider('urlProvider')] #[Test] public function it_makes_a_post_request($url)
    {
        // assemble
        $curl = new CurlService($url);
        $payload = [
            'key1' => 'value1',
        ];
        $jsonPayload = json_encode($payload);

        // act
        $response = $curl->post($jsonPayload);

        // assert
        $this->assertIsString($response);
    }

    public static function urlProvider(): array
    {
        return [
            ['https://example.com/'],
            [''],
        ];
    }
}

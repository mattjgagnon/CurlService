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
        // I realize this is redundant from above, but need to test setter
        $curl->setUrl($url);

        // act
        $response = $curl->get();
        $curl->close();
        $code = $curl->getInfo(CURLINFO_HTTP_CODE);
        $info = $curl->getInfo();
        $error = $curl->getError();

        // assert
        $this->assertIsString($response);
        $this->assertIsInt($code);
        $this->assertIsArray($info);
        $this->assertArrayHasKey('http_code', $info);
        $this->assertArrayHasKey('url', $info);
        $this->assertIsString($error);
    }

    #[DataProvider('urlProvider')] #[Test] public function it_makes_a_post_request($url)
    {
        // assemble
        $curl = new CurlService($url);
        $curl->setOptions([
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);
        $payload = [
            'key1' => 'value1',
        ];
        $jsonPayload = json_encode($payload);

        // act
        $response = $curl->post($jsonPayload);
        $curl->close();
        $errNo = $curl->getErrNo();

        // assert
        $this->assertIsString($response);
        $this->assertIsInt($errNo);
    }

    public static function urlProvider(): array
    {
        return [
            ['https://example.com/'],
            ['https://example.com/foo/'],
        ];
    }
}

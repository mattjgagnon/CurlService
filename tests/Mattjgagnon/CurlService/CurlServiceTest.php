<?php

namespace Mattjgagnon\CurlService;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlServiceTest extends TestCase
{
    #[Test] public function it_makes_a_delete_request()
    {
        // assemble
        $url = 'https://example.org/delete/1';
        $curl = new CurlService($url);

        // act
        $response = $curl->delete();
        $curl->close();

        // assert
        $this->assertIsString($response);
    }

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

    #[Test] public function it_makes_a_head_request()
    {
        // assemble
        $url = 'https://example.org/head/1';
        $curl = new CurlService($url);

        // act
        $response = $curl->head();
        $curl->close();

        // assert
        $this->assertIsString($response);
    }

    #[Test] public function a_get_request_with_an_invalid_url_throws_exception()
    {
        // assemble
        $curl = new CurlService();
        $this->expectException(InvalidArgumentException::class);
        $curl->setUrl('kjhfyr:iu7987tg');
    }

    #[Test] public function it_makes_an_options_request() {
        // assemble
        $url = 'https://example.com';
        $curl = new CurlService($url);

        // act
        $response = $curl->options();

        // assert
        $this->assertIsString($response);
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

    #[Test] public function it_makes_a_put_request()
    {
        // assemble
        $url = 'https://example.com/put';
        $curl = new CurlService();
        $curl->setUrl($url);
        $payload = [
            'key1' => 'value1',
        ];
        $jsonPayload = json_encode($payload);

        // act
        $response = $curl->put($jsonPayload);
        $curl->close();

        // assert
        $this->assertIsString($response);
    }

    public static function urlProvider(): array
    {
        return [
            ['https://example.com/'],
            ['https://example.com/foo/'],
        ];
    }
}

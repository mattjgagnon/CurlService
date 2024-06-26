<?php

namespace Mattjgagnon\CurlService;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurlServiceTest extends TestCase
{
    #[Test] public function it_makes_a_delete_request()
    {
        // assemble
        $url = 'https://example.org/delete/1';
        $curl = new CurlService($url);
        $this->expectException(CurlRetryException::class);

        // act
        $curl->delete();
    }

    #[Test] public function it_makes_a_get_request()
    {
        // assemble
        $url = 'https://example.org';
        $curl = new CurlService($url, 1, 3);
        // I realize this is redundant from above, but need to test setter
        $curl->setUrl($url);
        $curl->setMaxAttempts(2);
        $curl->setInitialBackoff(4);

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
        $this->expectException(CurlRetryException::class);

        // act
        $curl->head();
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

    #[Test] public function it_makes_a_post_request()
    {
        // assemble
        $url = 'https://example.org';
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
        $url = 'https://example.org/put';
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

    #[Test] public function a_get_request_with_an_invalid_max_attempts_throws_exception()
    {
        // assemble
        $curl = new CurlService();
        $this->expectException(InvalidArgumentException::class);
        $curl->setMaxAttempts(-1);
    }

    #[Test] public function a_get_request_with_an_invalid_initial_backoff_throws_exception()
    {
        // assemble
        $curl = new CurlService();
        $this->expectException(InvalidArgumentException::class);
        $curl->setInitialBackoff(-1);
    }
}

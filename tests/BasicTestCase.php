<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SeoAnalyzer\HttpClient\ClientInterface;

abstract class BasicTestCase extends BaseTestCase
{
    public function getClientMock(string|null $response = null): ClientInterface|MockObject
    {
        if (empty($response)) {
            $response = file_get_contents(__DIR__ . '/data/test.html');
        }
        $stream = $this->getMockBuilder(StreamInterface::class)->disableOriginalConstructor()->getMock();
        $stream->method('getContents')->willReturn($response);
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeader')->with('X-Guzzle-Redirect-History')->willReturn(['redirect' => 'redirect']);
        $clientMock = $this->getMockBuilder(ClientInterface::class)->getMock();
        $clientMock->method('get')->willReturn($response);
        return $clientMock;
    }
}

<?php

declare(strict_types=1);

namespace SeoAnalyzer\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

use Psr\Http\Message\ResponseInterface;
use SeoAnalyzer\HttpClient\Exception\HttpException;

class Client implements ClientInterface
{
    protected array $options = [
        'allow_redirects' => ['track_redirects' => true],
        'headers' => [
            'User-Agent' => 'grgk-seo-analyzer/1.0',
        ],
    ];

    public function get(string $url, array $options = []): ResponseInterface
    {
        if ($options === []) {
            $options = $this->options;
        }

        try {
            return (new GuzzleClient(['verify' => false]))->request('GET', $url, $options);
        } catch (GuzzleException $e) {
            throw new HttpException('Error getting url: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}

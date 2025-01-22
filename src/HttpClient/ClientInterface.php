<?php

declare(strict_types=1);

namespace SeoAnalyzer\HttpClient;

use Psr\Http\Message\ResponseInterface;
use SeoAnalyzer\HttpClient\Exception\HttpException;

interface ClientInterface
{
    /**
     * @return ResponseInterface
     * @throws HttpException
     */
    public function get(string $url, array $options = []): ResponseInterface;
}

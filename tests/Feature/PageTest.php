<?php

declare(strict_types=1);

namespace Tests\Feature;

use SeoAnalyzer\Page;

test('constructor', function (): void {
    $url = 'https://www.example.org';
    $html = '<html lang="en"><body><p>testing</p></body></html>';
    $clientMock = $this->getClientMock($html);
    $page = new Page($url, 'en_GB', $clientMock);
    expect($page)->toBeInstanceOf(Page::class);
    expect($page->content)->toEqual($html);
    expect($page->url)->toEqual($url);
});

test('get metrics pass with url', function (): void {
    $html = '<html lang="en"><body><p>testing</p></body></html>';
    $clientMock = $this->getClientMock($html);
    $page = new Page(null, null, $clientMock);
    $page->url = 'http://www.example.org';
    $metrics = $page->getMetrics();
    expect($metrics)->toBeArray();
});

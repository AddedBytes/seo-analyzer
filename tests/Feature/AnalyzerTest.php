<?php

declare(strict_types=1);

namespace Tests\Feature;

use InvalidArgumentException;
use SeoAnalyzer\Analyzer;
use SeoAnalyzer\HttpClient\Exception\HttpException;
use SeoAnalyzer\Metric\AbstractMetric;
use SeoAnalyzer\Page;


test('analyze url pass', function (): void {
    $clientMock = $this->getClientMock();
    $analyzer = new Analyzer(null, $clientMock);
    $results = $analyzer->analyzeUrl('http://www.example.org');
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    $this->assertStringContainsString('You should avoid redirects', $results['PageRedirect']['analysis']);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze url pass with keyword translated', function (): void {
    $clientMock = $this->getClientMock();
    $analyzer = new Analyzer(null, $clientMock);
    $results = $analyzer->analyzeUrl('http://www.example.org', 'keyword', 'pl_PL');
    expect(is_array($results))->toBeTrue();
    expect(count($results))->toEqual(count($analyzer->getMetrics()));
    $this->assertStringContainsString('Powinienieś unikać przekierowań', $results['PageRedirect']['analysis']);
});

test('analyze url with keyword pass', function (): void {
    $clientMock = $this->getClientMock();
    $analyzer = new Analyzer(null, $clientMock);
    $results = $analyzer->analyzeUrl('http://www.example.org', 'keyword');
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze url fail on invalid url', function (): void {
    $this->expectException(HttpException::class);
    $this->expectException(HttpException::class);

    (new Analyzer())->analyzeUrl('invalid-url');
});

test('analyze file pass', function (): void {
    $clientMock = $this->getClientMock();
    $analyzer = new Analyzer(null, $clientMock);
    $results = $analyzer->analyzeFile(dirname(__DIR__) . '/data/test.html');
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze html pass', function (): void {
    $clientMock = $this->getClientMock();
    $analyzer = new Analyzer(null, $clientMock);
    $htmlString =  file_get_contents(dirname(__DIR__) . '/data/test.html');
    $results = $analyzer->analyzeHtml($htmlString);
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze pass', function (): void {
    $page = new Page();
    $page->content = file_get_contents(dirname(__DIR__) . '/data/test.html');
    $analyzer = new Analyzer($page);
    $results = $analyzer->analyze();
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze fail on no page', function (): void {
    $this->expectException(InvalidArgumentException::class);
    // No
    $this->expectException(InvalidArgumentException::class);

    $analyzer = new Analyzer();
    $analyzer->analyze();
});

test('analyze pass in english as default', function (): void {
    $page = new Page();
    $page->content = '<html lang="en"></html>';
    $analyzer = new Analyzer($page);
    $results = $analyzer->analyze();
    expect($results['PageContentSize']['analysis'])->toEqual('The size of your page is ok');
});

test('analyze pass in polish', function (): void {
    $page = new Page();
    $page->content = '<html lang="en"></html>';
    $analyzer = new Analyzer($page);
    $analyzer->locale = 'pl_PL';
    $results = $analyzer->analyze();
    expect($results['PageContentSize']['analysis'])->toEqual('Rozmiar strony jest w porządku');
});

test('analyze pass on empty page content', function (): void {
    $page = new Page();
    $page->content = '<html lang="en"></html>';
    $analyzer = new Analyzer($page);
    $results = $analyzer->analyze();
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('analyze pass on invalid html', function (): void {
    $page = new Page();
    $page->content = "<html lang='en'><body><dif>hrad>><r\"o<!dif? \'dfgdf'''';< html>";
    $analyzer = new Analyzer($page);
    $results = $analyzer->analyze();
    expect(is_array($results))->toBeTrue();
    $this->assertSameSize($analyzer->getMetrics(), $results);
    expect(current($results))->toHaveKey('analysis');
    expect(current($results))->toHaveKey('name');
    expect(current($results))->toHaveKey('description');
    expect(current($results))->toHaveKey('value');
    expect(current($results))->toHaveKey('negative_impact');
});

test('get metrics pass', function (): void {
    $clientMock = $this->getClientMock();
    $page = new Page();
    $page->content = '<html lang="en"></html>';
    $analyzer = new Analyzer($page, $clientMock);
    $metrics = $analyzer->getMetrics();
    expect(is_array($metrics))->toBeTrue();
    foreach ($metrics as $metric) {
        expect($metric)->toBeInstanceOf(AbstractMetric::class);
    }
});

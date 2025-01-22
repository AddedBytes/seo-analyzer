<?php

declare(strict_types=1);

namespace Tests\Feature;

use SeoAnalyzer\Parser\ExampleCustomParser;
use SeoAnalyzer\Parser\Parser;

beforeEach(function (): void {
    $this->parser = new Parser(file_get_contents(dirname(__DIR__) . '/data/test.html'));
});

test('get meta pass', function (): void {
    $this->assertStringContainsString(
        '{"":"","description":"Some good, valid and proper testing description for our test site","viewport":"',
        json_encode($this->parser->getMeta(), JSON_THROW_ON_ERROR)
    );
});

test('get headers pass', function (): void {
    $this->assertStringContainsString(
        '{"h1":["Header tells about testing"],"h2":["We like testing","Search engine optimization"],"h3":["',
        json_encode($this->parser->getHeaders(), JSON_THROW_ON_ERROR)
    );
});

test('get title pass', function (): void {
    expect($this->parser->getTitle())->toEqual('Testing title');
});

test('get alts pass', function (): void {
    $this->assertStringContainsString(
        '["see me testing","check it out","description of image"]',
        json_encode($this->parser->getAlts(), JSON_THROW_ON_ERROR)
    );
});

test('get text pass', function (): void {
    $text = $this->parser->getText();
    expect(strlen((string) $text))->toEqual(3857);
    $this->assertStringContainsString('Testing title Header tells about testing We like testing Search engine', $text);
    $this->assertStringContainsString('SEO may target different kinds of search, including image search, video search', $text);
    $this->assertStringContainsString('increase its relevance to specific keywords and to remove barriers', $text);
    $this->assertStringContainsString('increase its relevance to specific keywords and to remove barriers', $text);
    $this->assertStringNotContainsString('alert', $text);
    $this->assertStringNotContainsString('testAlert', $text);
    $this->assertStringNotContainsString('<html>', $text);
    $this->assertStringNotContainsString('<style>', $text);
    $this->assertStringNotContainsString('<script>', $text);
    $this->assertStringNotContainsString('<h2>', $text);
});

test('example custom parser get alts pass', function (): void {
    $parser = new ExampleCustomParser(file_get_contents(dirname(__DIR__) . '/data/test.html'));
    $this->assertStringContainsString(
        '[{"alt":"see me testing","src":"image.jpg"},{"alt":"check it out","src":"image.jpg"},{"alt":"description',
        json_encode($parser->getAlts(), JSON_THROW_ON_ERROR)
    );
});

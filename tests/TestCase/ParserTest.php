<?php

namespace Tests\TestCase;

use SeoAnalyzer\Parser\ExampleCustomParser;
use SeoAnalyzer\Parser\Parser;
use Tests\TestCase;

class ParserTest extends TestCase
{
    private Parser $parser;

    public function setUp(): void
    {
        parent::setUp();
        $this->parser = new Parser(file_get_contents(dirname(__DIR__) . '/data/test.html'));
    }

    public function testGetMetaPass()
    {
        $this->assertStringContainsString(
            '{"":"","description":"Some good, valid and proper testing description for our test site","viewport":"',
            json_encode($this->parser->getMeta(), JSON_THROW_ON_ERROR)
        );
    }

    public function testGetHeadersPass()
    {
        $this->assertStringContainsString(
            '{"h1":["Header tells about testing"],"h2":["We like testing","Search engine optimization"],"h3":["',
            json_encode($this->parser->getHeaders(), JSON_THROW_ON_ERROR)
        );
    }

    public function testGetTitlePass()
    {
        $this->assertEquals(
            'Testing title',
            $this->parser->getTitle()
        );
    }

    public function testGetAltsPass()
    {
        $this->assertStringContainsString(
            '["see me testing","check it out","description of image"]',
            json_encode($this->parser->getAlts(), JSON_THROW_ON_ERROR)
        );
    }

    public function testGetTextPass()
    {
        $text = $this->parser->getText();
        $this->assertEquals(3857, strlen((string) $text));
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
    }

    public function testExampleCustomParserGetAltsPass()
    {
        $parser = new ExampleCustomParser(file_get_contents(dirname(__DIR__) . '/data/test.html'));
        $this->assertStringContainsString(
            '[{"alt":"see me testing","src":"image.jpg"},{"alt":"check it out","src":"image.jpg"},{"alt":"description',
            json_encode($parser->getAlts(), JSON_THROW_ON_ERROR)
        );
    }
}

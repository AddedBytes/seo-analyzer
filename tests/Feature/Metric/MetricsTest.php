<?php

declare(strict_types=1);

namespace Tests\Feature\Metric;

use SeoAnalyzer\Analyzer;
use SeoAnalyzer\Metric\MetricFactory;

beforeEach(function (): void {
    $analyzer = new Analyzer();
    $analyzer->setUpTranslator('pl_PL');
    $this->translator = $analyzer->translator;
});

test('analyze pass', function (string $metricKey, mixed $input, array $expected): void {
    $metric = MetricFactory::get($metricKey, $input);
    expect($metric)->toBeInstanceOf($expected['class']);
    $analysis = $metric->analyze();
    if (isset($expected['value'])) {
        expect($metric->value)->toBe($expected['value']);
    }
    expect($metric->impact)->toEqual($expected['impact']);
    $this->assertStringContainsString($expected['analysis'], $analysis);
    $this->assertNotEquals($metric->description, $this->translator->trans($metric->description));
    $this->assertNotEquals($analysis, $this->translator->trans($analysis));
})->with('metricsDataProvider');

dataset('metricsDataProvider', fn(): array => array_merge(
    require_once 'metricsTestData/file.php',
    require_once 'metricsTestData/keywords.php',
    require_once 'metricsTestData/page.php',
    require_once 'metricsTestData/pageHeaders.php',
    require_once 'metricsTestData/pageMeta.php'
));

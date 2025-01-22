<?php

declare(strict_types=1);

namespace Tests\Feature\Metric\Page\Content;

use SeoAnalyzer\Metric\Page\Content\SizeMetric;

test('analyze pass', function ($value, array $expected): void {
    $metric = new SizeMetric($value);
    $message = $metric->analyze();
    $this->assertStringContainsString($expected['message'], $message);
    expect($expected['impact'])->toEqual($metric->impact);
})->with('metricsDataProvider');

dataset('metricsDataProvider', static fn(): array => [
    [false, ['message' => 'Can not read your page content', 'impact' => 10]],
    [0, ['message' => 'Looks that your site content is empty', 'impact' => 10]],
    [1000, ['message' => 'The size of your page is ok', 'impact' => 0]],
    [30000, ['message' => 'The size of your page is ok', 'impact' => 0]],
    [30001, ['message' => 'You should consider some optimisation of the page to decrease it', 'impact' => 1]],
    [80000, ['message' => 'You should consider some optimisation of the page to decrease it', 'impact' => 1]],
    [80001, ['message' => 'The site is very big. You should consider rebuilding the page to', 'impact' => 3]],
]);

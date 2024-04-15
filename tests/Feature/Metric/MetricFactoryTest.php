<?php

declare(strict_types=1);

namespace Tests\Feature\Metric;

use ReflectionException;
use SeoAnalyzer\Metric\MetricFactory;
use SeoAnalyzer\Metric\Page\Content\SizeMetric;


test('get pass', function () {
    $metric = MetricFactory::get('page.content.size', 4076);
    expect($metric)->toBeInstanceOf(SizeMetric::class)
        ->and($metric->description)->toEqual('The size of the page')
        ->and($metric->value)->toEqual(4076);
});

test('get fail on not existing class', function () {
    $this->expectException(ReflectionException::class);

    MetricFactory::get('page.not_existing', 4076);
});

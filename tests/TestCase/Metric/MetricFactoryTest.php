<?php

use SeoAnalyzer\Metric\MetricFactory;
use SeoAnalyzer\Metric\Page\Content\SizeMetric;
use Tests\TestCase\Metric\Mock\MissingNameTestMetric;


test('get pass', function () {
    $metric = MetricFactory::get('page.content.size', 4076);
    expect($metric)->toBeInstanceOf(SizeMetric::class);
    expect($metric->description)->toEqual('The size of the page');
    expect($metric->value)->toEqual(4076);
});

test('get fail on not existing class', function () {
    $this->expectException(ReflectionException::class);

    MetricFactory::get('page.not_existing', 4076);
});

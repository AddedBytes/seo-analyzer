<?php

use Tests\TestCase\Metric\Mock\ConditionsCallableTestMetric;


test('analyze pass with metrics callable conditions', function () {
    $metric = new ConditionsCallableTestMetric('not empty value');
    expect($metric->analyze())->toEqual('Success test metric output message');
    expect(0)->toEqual($metric->impact);
});

test('analyze fail with metrics callable conditions', function () {
    $metric = new ConditionsCallableTestMetric(null);
    expect($metric->analyze())->toEqual('Fail test metric output message');
    expect(4)->toEqual($metric->impact);
});

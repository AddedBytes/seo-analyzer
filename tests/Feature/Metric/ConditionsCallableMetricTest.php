<?php

declare(strict_types=1);

namespace Tests\Feature\Metric;

use Tests\Feature\Metric\Mock\ConditionsCallableTestMetric;


test('analyze pass with metrics callable conditions', function (): void {
    $metric = new ConditionsCallableTestMetric('not empty value');
    expect($metric->analyze())->toEqual('Success test metric output message')
        ->and(0)->toEqual($metric->impact);
});

test('analyze fail with metrics callable conditions', function (): void {
    $metric = new ConditionsCallableTestMetric(null);
    expect($metric->analyze())->toEqual('Fail test metric output message')
        ->and(4)->toEqual($metric->impact);
});

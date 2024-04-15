<?php

declare(strict_types=1);

namespace Tests\Feature\Metric\Mock;

use SeoAnalyzer\Metric\AbstractMetric;

class ConditionsCallableTestMetric extends AbstractMetric
{
    public string $description = 'Test metric';

    public function __construct($inputData)
    {
        parent::__construct($inputData);
        $this->results = [
            'test_condition' => [
                'condition' => static function ($value) {
                    if (empty($value)) {
                        return true;
                    }
                    return false;
                },
                'impact' => 4,
                'message' => 'Fail test metric output message'
            ]
        ];
    }

    public function analyze(): string
    {
        return $this->checkTheResults('Success test metric output message');
    }
}

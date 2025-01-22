<?php

declare(strict_types=1);

namespace SeoAnalyzer\Metric;

interface MetricInterface
{
    /**
     * Returns description of the results of metric analysis.
     *
     * @return string
     */
    public function analyze(): string;
}

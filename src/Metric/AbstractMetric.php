<?php

declare(strict_types=1);

namespace SeoAnalyzer\Metric;

use ReflectionClass;

abstract class AbstractMetric implements MetricInterface
{
    final public const string HEADERS = 'headers';
    final public const string DESCRIPTION = 'description';
    final public const string IMPACT = 'impact';
    final public const string MESSAGE = 'message';

    /**
     * @var array Possible results configuration.
     */
    protected array $results = [];

    /**
     * @var string Metric name
     */
    public string|array $name;

    /**
     * @var string Metric description
     */
    public string $description;

    public int $impact = 0;

    public function __construct(public mixed $value)
    {
        if (! isset($this->name) || ($this->name === '' || $this->name === '0' || $this->name === [])) {
            $this->name = str_replace(['SeoAnalyzer\\', 'Metric', '\\'], '', (new ReflectionClass($this))->getName());
        }
    }

    /**
     * Sets up the conditions for results configured.
     *
     * @return bool
     */
    protected function setUpResultsConditions(array $conditions): bool
    {
        foreach ($conditions as $key => $condition) {
            $this->results[$key]['condition'] = $condition;
        }

        return true;
    }

    /**
     * Checks if any of the possible defined results occurred.
     *
     * @param string $defaultMessage Default message to return
     * @return string Result message
     */
    protected function checkTheResults(string $defaultMessage): string
    {
        foreach ($this->results as $result) {
            if ($this->isResultExpected($result['condition'])) {
                $this->impact = $result['impact'];

                return $result['message'];
            }
        }

        return $defaultMessage;
    }

    private function isResultExpected(mixed $condition): bool
    {
        if (is_callable($condition)) {
            return $condition($this->value);
        }

        return $condition;

    }
}

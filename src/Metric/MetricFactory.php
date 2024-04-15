<?php

namespace SeoAnalyzer\Metric;

use ReflectionException;

class MetricFactory
{
    /**
     * @return mixed
     * @throws ReflectionException
     */
    public static function get(string $key, mixed $inputData = null): mixed
    {
        $class = __NAMESPACE__;
        $path  = explode(".", $key);
        foreach ($path as $level) {
            if(strtolower($level) === 'ssl') {
                $class .= '\\' . strtoupper($level);

                continue;
            }
            $class .= '\\' . ucfirst($level);
        }
        $class .= 'Metric';
        if (class_exists($class)) {
            return new $class($inputData);
        }

        throw new ReflectionException('Metric class ' . $class . ' not exists');
    }
}

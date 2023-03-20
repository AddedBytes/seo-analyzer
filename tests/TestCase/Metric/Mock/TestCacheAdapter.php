<?php

namespace Tests\TestCase\Metric\Mock;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class TestCacheAdapter extends FilesystemAdapter
{
    /**
     * {@inheritdoc}
     */
//    public function get($key, $default = null)
    public function get(string $key, callable $callback, ?float $beta = null, ?array &$metadata = null): mixed
    {
        throw new InvalidArgumentException();
    }
}

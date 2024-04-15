<?php

declare(strict_types=1);

namespace Tests\Feature\Metric\Mock;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class TestCacheAdapter extends FilesystemAdapter
{
    /**
     * {@inheritdoc}
     */
//    public function get($key, $default = null)
    public function get(string $key, callable $callback, float|null $beta = null, array|null &$metadata = null): mixed
    {
        throw new InvalidArgumentException();
    }
}

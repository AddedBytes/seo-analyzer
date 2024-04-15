<?php

namespace SeoAnalyzer;

use Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class Cache
{
    /**
     * @var Psr16Cache
     */
    public Psr16Cache $adapter;

    public function __construct(string|null $adapterClass = null, $ttl = 300)
    {
        if (empty($adapterClass)) {
            $adapterClass = FilesystemAdapter::class;
        }
        $this->adapter = new Psr16Cache(new $adapterClass('seoanalyzer', $ttl));
    }

    /**
     * @param string $key Cache key
     * @param callable $callback Function that return data to be cached if cache empty
     * @param int|null $ttl Cache time in seconds. If empty global Cache ttl is used.
     * @return mixed
     */
    public function remember(string $key, callable $callback, int|null $ttl = null): mixed
    {
        $value = $this->get($key);
        if (empty($value)) {
            $value = $callback();
            if ($value !== false) {
                $this->set($key, $value, $ttl);
            }
        }

        return $value;
    }

    /**
     * Returns cached item or false it no cache found for that key.
     *
     * @return bool|mixed
     */
    public function get(string $cacheKey): mixed
    {
        $value = false;

        try {
            $hasKey = $this->adapter->has($cacheKey);
        } catch (Exception) {
            return false;
        }
        if ($hasKey) {
            try {
                $value = $this->adapter->get($cacheKey);
            } catch (Exception) {
                return false;
            }
        }

        return $value;
    }

    /**
     * Stores value in cache.
     *
     * @param $value
     * @param $ttl
     */
    public function set(string $cacheKey, $value, $ttl = null): bool
    {
        try {
            return $this->adapter->set($cacheKey, $value, $ttl);
        } catch (Exception) {
            return false;
        }
    }
}

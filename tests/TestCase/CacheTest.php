<?php

namespace Tests\TestCase;

use Tests\TestCase;
use SeoAnalyzer\Cache;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class CacheTest extends TestCase
{
    public function testRememberPass()
    {
        $cache = new Cache();
        $cache->adapter->delete('testCacheKey');
        $value = $cache->remember('testCacheKey', function () {
            return 'initialValue';
        });
        $this->assertEquals('initialValue', $value);
        $valueCached = $cache->remember('testCacheKey', function () {
            return 'newValue';
        });
        $this->assertEquals('initialValue', $valueCached);
        $cache->adapter->delete('testCacheKey');
        $valueRefreshed = $cache->remember('testCacheKey', function () {
            return 'newValueOneMoreTime';
        });
        $this->assertEquals('newValueOneMoreTime', $valueRefreshed);
    }
}

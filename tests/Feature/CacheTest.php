<?php

declare(strict_types=1);

namespace Tests\Feature;

use SeoAnalyzer\Cache;


test('remember pass', function (): void {
    $cache = new Cache();
    $cache->adapter->delete('testCacheKey');
    $value = $cache->remember('testCacheKey', fn(): string => 'initialValue');
    expect($value)->toEqual('initialValue');
    $valueCached = $cache->remember('testCacheKey', fn(): string => 'newValue');
    expect($valueCached)->toEqual('initialValue');
    $cache->adapter->delete('testCacheKey');
    $valueRefreshed = $cache->remember('testCacheKey', fn(): string => 'newValueOneMoreTime');
    expect($valueRefreshed)->toEqual('newValueOneMoreTime');
});

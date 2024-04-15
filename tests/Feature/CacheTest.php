<?php

declare(strict_types=1);

namespace Tests\Feature;

use SeoAnalyzer\Cache;


test('remember pass', function () {
    $cache = new Cache();
    $cache->adapter->delete('testCacheKey');
    $value = $cache->remember('testCacheKey', fn() => 'initialValue');
    expect($value)->toEqual('initialValue');
    $valueCached = $cache->remember('testCacheKey', fn() => 'newValue');
    expect($valueCached)->toEqual('initialValue');
    $cache->adapter->delete('testCacheKey');
    $valueRefreshed = $cache->remember('testCacheKey', fn() => 'newValueOneMoreTime');
    expect($valueRefreshed)->toEqual('newValueOneMoreTime');
});

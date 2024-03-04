<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait CacheHandler
{

    public function getCacheKeyFromRequest($request): string
    {

        $url = $request->url();
        $queryParams = $request->query();
        $path = $request->path();

        $prefix = $request->route()->getPrefix();
        $params = str_replace($prefix, '', $path);
        $params = array_filter(explode('/', $params), 'strlen');

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        $rememberKey = '';

        foreach ($params as $param) {
            $rememberKey.= $param.':';
        }

        $rememberKey .= sha1($fullUrl);

        return $rememberKey;
    }

    public function clearCacheContainingKey(string $rememberKey): void
    {

        // get cache driver
        $cacheDriver = config('cache.default');

        info('cache driver: '.$cacheDriver);

        if ($cacheDriver == 'redis') {
            $this->clearRedisCacheContainingKey($rememberKey);
        } else {
            $this->clearLaravelCacheContainingKey($rememberKey);
        }

    }

    public function clearRedisCacheContainingKey ($rememberKey)
    {
        $keys = Redis::connection('cache')->keys('*'.$rememberKey.':*');

        info('REDIS cache keys: '.json_encode($keys));

        foreach ($keys as $key) {

            $key = explode(':', $key, 2)[1];
            info('deleting key: '.$key);

            Cache::forget($key);
        }
    }

    public function clearLaravelCacheContainingKey($rememberKey)
    {

        $keys = Cache::store('file')->get('cache-keys', []);

        info('ARRAY cache keys: '.json_encode($keys));

        foreach ($keys as $key) {
            if (strpos($key, $rememberKey) !== false) {
                info('deleting key: '.$key);
                Cache::forget($key);
            }
        }
    }
}

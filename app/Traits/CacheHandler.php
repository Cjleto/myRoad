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
        $keys = Redis::connection('cache')->keys('*'.$rememberKey.':*');

        info($keys);

        foreach ($keys as $key) {

            $key = explode(':', $key, 2)[1];
            info('deleting key: '.$key);

            Cache::forget($key);
        }
    }
}

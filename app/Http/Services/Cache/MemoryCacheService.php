<?php

namespace App\Http\Services\Cache;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MemoryCacheService implements ICache
{
    private int $cacheTime;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->cacheTime = 3600;
    }


    /**
     * get
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return array
     */
    public function get($key, $tags = null, $default = null)
    {
        if (!is_string(($key))) {
            $key = (string)$key;
        }
        if (isset($tags)) {
            $item =  Cache::tags($tags)->get($key);
        } else {
            $item =  Cache::get($key, $default);
        }
        if (isset($item))
            return (json_decode($item));
        return $item;
    }

    /**
     * set
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function set($key,  $value, $tags = null)
    {
        if (!is_string(($key))) {
            $key = (string)$key;
        }
        $data = $value->toJson();
        if (isset($tags))
            $item =   Cache::tags($tags)->put($key, $data,  $this->cacheTime);
        $item =   Cache::put($key, $data,  $this->cacheTime);
        return $item;
    }

    /**
     * isSet
     *
     * @param  mixed $key
     * @return bool
     */
    public function isSet($key)
    {
        if (!is_string(($key))) {
            $key = (string)$key;
        }
        return Cache::has($key);
    }

    /**
     * remove
     *
     * @param  mixed $key
     * @return bool
     */
    public function remove($key, $tags = null)
    {
        try {
            if (!is_string(($key))) {
                $key = (string)$key;
            }
            if (isset($tags)) {
                Cache::tags($tags)->flush();
            }
            Cache::forget($key);
            return true;
        } catch (Exception $e) {
            Log::debug($e);
            return false;
        }
    }

    /**
     * removeByPattern
     *
     * @param  mixed $tags
     * @return bool
     */
    public function removeByPattern($tags)
    {
        try {
            Cache::tags($tags)->flush();
            return true;
        } catch (Exception $e) {
            Log::debug($e);
            return false;
        }
    }

    /**
     * clear
     *
     * @return bool
     */
    public function clear()
    {
        try {
            Cache::flush();
            return true;
        } catch (Exception $e) {
            Log::debug($e);
            return false;
        }
    }
}

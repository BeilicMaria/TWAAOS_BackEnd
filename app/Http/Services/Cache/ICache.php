<?php

namespace App\Http\Services\Cache;

interface ICache
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     */
    public function get($key, $default = null);

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store. Must be serializable.
     *  @param array                 $tag
     * @return bool True on success and false on failure.
     *
     */
    public function set($key,  $value, $tags = [null]);

    /**
     * Determines whether an item is present in the cache.
     *
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     */
    public function isSet($key);


    /**
     * remove Delete an item from the cache by its unique key.
     *
     * @param  mixed $key  $key The unique cache key of the item to delete.
     * @param  mixed $tags
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function remove($key, $tags = null);



    /**
     * removeByPattern
     *
     * @param  mixed $tags
     * @return void
     */
    public function removeByPattern(array $tags);

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear();
}

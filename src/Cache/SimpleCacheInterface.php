<?php

namespace Odan\Cache;

/**
 * SimpleCacheInterface (DRAFT for PCS)
 */
interface SimpleCacheInterface
{

    /**
     * Set key to hold the value. If key already holds a value,
     * it is overwritten, regardless of its type.
     *
     * A key cannot be null, but a value can be.
     *
     * @param string $key Key.
     * @param mixed $value Value to store.
     * @param int $ttl The specified expire time, in seconds.
     * @return bool
     * @throws ArgumentNullException If the key is null.
     */
    public function set($key, $value, $ttl = null);

    /**
     * Get the value of key.
     * If the key does not exist the $default value (null) is returned.
     *
     * A key cannot be null, but a value can be.
     *
     * @param string $key Key.
     * @param mixed $default Default return value.
     * @return mixed
     * @throws ArgumentNullException If the key is null.
     */
    public function get($key, $default = null);

    /**
     * Returns if key exists.
     *
     * @param string $key Key.
     * @return bool True if the key exists.
     */
    public function has($key);

    /**
     * Removes the specified key. A key is ignored if it does not exist.
     *
     * @param string $key Key.
     * @return bool
     */
    public function remove($key);

    /**
     * Clear cache.
     *
     * @return bool
     */
    public function clear();
}

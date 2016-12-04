<?php

namespace Odan\Cache;

use Exception;

/**
 * MemoryCache
 */
class MemoryCache implements SimpleCacheInterface
{

    /**
     * Memory cache data
     *
     * @var array
     */
    protected $cache = array();

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        if ($key === null) {
            throw new Exception('Argument Null Exception');
        }
        // TODO: Implement TTL parameter
        $this->cache[$key] = $value;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if ($key === null) {
            // TODO: Implement a real ArgumentNullException class
            throw new Exception('Argument Null Exception');
        }
        // TODO: Implement TTL parameter
        return $this->has($key) ? $this->cache[$key] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->cache);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->cache[$key]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->cache = array();
        return true;
    }

}

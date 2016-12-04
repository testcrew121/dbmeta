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
        $this->cache[$key] = $this->createCacheValue($key, $value, $ttl);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if ($key === null) {
            throw new Exception('Argument Null Exception');
        }
        if (!array_key_exists($key, $this->cache)) {
            return $default;
        }
        $cacheValue = $this->cache[$key];
        if ($this->isExpired($cacheValue['expires'])) {
            $this->remove($key);
            return $default;
        }
        return isset($cacheValue['value']) ? $cacheValue['value'] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        if ($key === null) {
            throw new Exception('Argument Null Exception');
        }
        if (!array_key_exists($key, $this->cache)) {
            return false;
        }
        $cacheValue = $this->cache[$key];
        if ($this->isExpired($cacheValue['expires'])) {
            $this->remove($key);
            return false;
        }
        return true;
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

    /**
     * Creates a FileSystemCacheValue object.
     *
     * @param mixed $key The cache key the file is stored under.
     * @param mixed $value The data being stored
     * @param int $ttl The timestamp of when the data will expire.  If null, the data won't expire.
     * @return array Cache value
     */
    protected function createCacheValue($key, $value, $ttl = null)
    {
        $created = time();
        return array(
            'created' => $created,
            'key' => $key,
            'value' => $value,
            'ttl' => $ttl,
            'expires' => ($ttl) ? $created + $ttl : null
        );
    }

    /**
     * Checks if a value is expired
     * @return bool True if the value is expired.  False if it is not.
     */
    protected function isExpired($expires)
    {
        //value doesn't expire
        if (!$expires) {
            return false;
        }

        //if it is after the expire time
        return time() > $expires;
    }

}

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
        $chacheKey = $this->createCacheKey($key);
        $this->cache[$chacheKey] = $this->createCacheValue($key, $value, $ttl);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $chacheKey = $this->createCacheKey($key);
        if (!array_key_exists($chacheKey, $this->cache)) {
            return $default;
        }
        $cacheValue = $this->cache[$chacheKey];
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
        $chacheKey = $this->createCacheKey($key);
        if (!array_key_exists($chacheKey, $this->cache)) {
            return false;
        }
        $cacheValue = $this->cache[$chacheKey];
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
        $chacheKey = $this->createCacheKey($key);
        unset($this->cache[$chacheKey]);
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
     * Create cache key.
     *
     * @param mixed $key
     * @return string
     * @throws Exception
     */
    protected function createCacheKey($key)
    {
        if ($key === null) {
            throw new Exception('Argument Null Exception');
        }
        return serialize($key);
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

<?php

namespace Odan\Cache;

use Exception;
use RecursiveIteratorIterator;
use FilesystemIterator;
use RecursiveDirectoryIterator;

/**
 * OpCache
 *
 * OPcache improves PHP performance by storing precompiled script bytecode
 * in shared memory, thereby removing the need for PHP to load and
 * parse scripts on each request.
 */
class OpCache implements SimpleCacheInterface
{

    /**
     * Cache path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Constructor
     *
     * @param string $path Cache path
     */
    public function __construct($path = null)
    {
        if (isset($path)) {
            $this->path = $path;
        } else {
            $this->path = sys_get_temp_dir() . '/opcache';
        }
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        if ($key === null) {
            throw new Exception('Argument Null Exception');
        }

        $cacheFile = $this->getFilename($key);
        $path = dirname($cacheFile);
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        $cacheValue = $this->getCacheValue($key, $value, $ttl);
        $content = var_export($cacheValue, true);

        // HHVM fails at __set_state, so just use object cast for now
        $content = str_replace('stdClass::__set_state', '(object)', $content);
        $content = '<?php return ' . $content . ';';

        file_put_contents($cacheFile, $content);

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
        $filename = $this->getFilename($key);
        if (!file_exists($filename)) {
            return $default;
        }
        $cacheValue = include $filename;
        if ($this->isExpired($cacheValue['expires'])) {
            $this->remove($key);
            return $default;
        }
        $result = isset($cacheValue['value']) ? $cacheValue['value'] : $default;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $filename = $this->getFilename($key);
        return file_exists($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $filename = $this->getFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        return true;
    }

    /**
     * Get cache filename.
     *
     * @param mixed $key
     * @return string Filename
     */
    protected function getFilename($key)
    {
        $sha1 = sha1(implode('', (array) $key));
        $result = $this->path . '/' . substr($sha1, 0, 2) . '/' . substr($sha1, 2) . '.php';
        return $result;
    }

    /**
     * Creates a FileSystemCacheValue object.
     *
     * @param FileSystemCacheKey $key The cache key the file is stored under.
     * @param mixed $value The data being stored
     * @param int $ttl The timestamp of when the data will expire.  If null, the data won't expire.
     */
    protected function getCacheValue($key, $value, $ttl = null)
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

<?php

namespace Odan\Cache;

use SQLite3;

/**
 * Simple key/value interface for SQLite3
 *
 * @author odan
 * @license MIT
 * @link https://github.com/odan/dbmeta
 */
class SqliteCache implements SimpleCacheInterface
{

    /**
     * Filename
     *
     * @var string
     */
    protected $file;

    /**
     * Database
     *
     * @var SQLite3
     */
    protected $db;

    /**
     * Constructor
     *
     * @param string $file Database file
     */
    public function __construct($file)
    {
        if (!isset($file)) {
            throw new Exception('Parameter file required');
        }
        $this->open($file);
    }

    /**
     * Connect to database
     *
     * @param string $file
     */
    public function open($file)
    {
        $this->file = $file;
        $this->db = new SQLite3($this->file);
        $this->install();
    }

    /**
     * Install table
     *
     * @return bool
     */
    protected function install()
    {
        return $this->db->exec("CREATE TABLE IF NOT EXISTS meta(
            meta_key TEXT PRIMARY KEY,
            meta_value BLOB)");
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        $cacheKey = $this->createCacheKey($key);
        $cacheValue = $this->createCacheValue($key, $value, $ttl);
        /* @var $st SQLite3Stmt */
        $st = $this->db->prepare('INSERT OR REPLACE INTO meta VALUES(?,?)');
        $st->bindParam(1, $cacheKey, SQLITE3_TEXT);
        $st->bindParam(2, $cacheValue, SQLITE3_TEXT);
        $result = $st->execute() !== false;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $cacheKey = $this->createCacheKey($key);
        $st = $this->db->prepare('SELECT meta_value FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $cacheKey, SQLITE3_TEXT);
        $row = $st->execute()->fetchArray(SQLITE3_ASSOC);
        if (!isset($row['meta_value'])) {
            return $default;
        }
        $cacheValue = $this->decodeValue($row['meta_value']);
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
        $cacheKey = $this->createCacheKey($key);
        $st = $this->db->prepare('SELECT 1,meta_value FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $cacheKey, SQLITE3_TEXT);
        $row = $st->execute()->fetchArray(SQLITE3_ASSOC);
        if (empty($row)) {
            return false;
        }
        $cacheValue = $this->decodeValue($row['meta_value']);
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
        $cacheKey = $this->createCacheKey($key);
        $st = $this->db->prepare('DELETE FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $cacheKey, SQLITE3_TEXT);
        $result = $st->execute() !== false;
        return $result;
    }

    /**
     * Returns json string from value
     *
     * @param mixed $value
     * @return string
     */
    protected function encodeValue($value)
    {
        return serialize($value);
    }

    /**
     * Returns array from json string
     *
     * @param string $value
     * @return mixed
     */
    protected function decodeValue($value)
    {
        return unserialize($value);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->db->exec("DROP TABLE meta;");
        $this->install();
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
        return $this->encodeValue($key);
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
        $data = array(
            'created' => $created,
            'key' => $key,
            'value' => $value,
            'ttl' => $ttl,
            'expires' => ($ttl) ? $created + $ttl : null
        );
        return $this->encodeValue($data);
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

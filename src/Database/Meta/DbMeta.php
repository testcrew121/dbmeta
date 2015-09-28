<?php

namespace Odan\Database\Meta;

use SQLite3;

/**
 * Simple and easy key/value storage with SQLite3
 *
 * @author odan
 * @license MIT
 * @link https://github.com/odan/dbmeta
 */
class DbMeta
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
     * Connect to database
     *
     * @param string $file
     */
    public function connect($file)
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
     * Set key and value
     *
     * @param mixed $key
     * @param mixed $value
     * @return bool
     */
    public function set($key, $value)
    {
        $key = $this->encodeJson($key);
        $value = $this->encodeJson($value);
        /* @var $st SQLite3Stmt */
        $st = $this->db->prepare('INSERT OR REPLACE INTO meta VALUES(?,?)');
        $st->bindParam(1, $key, SQLITE3_TEXT);
        $st->bindParam(2, $value, SQLITE3_TEXT);
        $result = $st->execute() !== false;
        return $result;
    }

    /**
     * Returns value by key
     *
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->encodeJson($key);
        $st = $this->db->prepare('SELECT meta_value FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $key, SQLITE3_TEXT);
        $row = $st->execute()->fetchArray();
        $result = null;
        if (isset($row['meta_value'])) {
            $result = $this->decodeJson($row['meta_value']);
        }
        return $result;
    }

    /**
     * Returns true if key exist
     *
     * @param mixed $key
     * @return bool
     */
    public function exist($key)
    {
        $key = $this->encodeJson($key);
        $st = $this->db->prepare('SELECT 1 FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $key, SQLITE3_TEXT);
        $row = $st->execute()->fetchArray();
        $result = !empty($row);
        return $result;
    }

    /**
     * Delete key
     *
     * @param mixed $key
     * @return bool
     */
    public function delete($key)
    {
        $key = $this->encodeJson($key);
        $st = $this->db->prepare('DELETE FROM meta WHERE meta_key=?;');
        $st->bindParam(1, $key, SQLITE3_TEXT);
        $result = $st->execute() !== false;
        return $result;
    }

    /**
     * Returns json string from value
     *
     * @param mixed $value
     * @return string
     */
    protected function encodeJson($value)
    {
        return json_encode($this->encodeUtf8($value), 0);
    }

    /**
     * Returns array from json string
     *
     * @param string $json
     * @return array
     */
    protected function decodeJson($json)
    {
        return json_decode($json, true);
    }

    /**
     * Returns a UTF-8 encoded string or array
     *
     * @param mixed $mix
     * @return mixed
     */
    protected function encodeUtf8($mix)
    {
        if ($mix === null || $mix === '') {
            return $mix;
        }
        if (is_array($mix)) {
            foreach ($mix as $key => $val) {
                $mix[$key] = $this->encodeUtf8($val);
            }
            return $mix;
        } else {
            if (!mb_check_encoding($mix, 'UTF-8')) {
                return mb_convert_encoding($mix, 'UTF-8');
            } else {
                return $mix;
            }
        }
    }
}

<?php

/**
 * SqliteCache Test
 */
class DbMetaSqliteTest extends MemoryCacheTest
{
    protected $cache;

    protected function setUp()
    {
        $file = dirname(__DIR__) . '/tmp/test.db';
        $path = dirname($file);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $this->cache = new \Odan\Cache\SqliteCache($file);
        $this->cache->clear();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(\Odan\Cache\SqliteCache::class, $this->cache);
    }
}
<?php

/**
 * OpCacheTest
 */
class OpCacheTest extends MemoryCacheTest
{
    protected $cache;

    protected function setUp()
    {
        $path = dirname(__DIR__) . '/tmp/opcache';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $this->cache = new \Odan\Cache\OpCache($path);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(\Odan\Cache\OpCache::class, $this->cache);
    }
}
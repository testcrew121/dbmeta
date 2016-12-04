<?php

/**
 * OpCacheTest
 */
class OpCacheTest extends PHPUnit_Framework_TestCase
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

    public function testHasByStringKey()
    {
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->has('key'));
    }

    public function testHasByIntKey()
    {
        $this->cache->set(1, 'value');
        $this->assertTrue($this->cache->has(1));
    }

    public function testGetByStringKey()
    {
        $this->cache->set('strkey', 'str key value');
        $this->assertEquals('str key value', $this->cache->get('strkey'));
    }

    public function testGetByIntKey()
    {
        $this->cache->set(2, 'value 2');
        $this->assertEquals('value 2', $this->cache->get(2));
    }

    public function testRemoveKey()
    {
        $this->cache->set('fordeletekey', 'value');
        $this->assertTrue($this->cache->has('fordeletekey'));
        $this->cache->remove('fordeletekey');
        $this->assertFalse($this->cache->has('fordeletekey'));
    }

}

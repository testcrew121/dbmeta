<?php

/**
 * MemoryCacheTest
 */
class MemoryCacheTest extends PHPUnit_Framework_TestCase
{
    protected $cache;

    protected function setUp()
    {
        $this->cache = new \Odan\Cache\MemoryCache();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(\Odan\Cache\MemoryCache::class, $this->cache);
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

    public function testHasStringKey()
    {
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->has('key'));
    }

    public function testHasArrayKey()
    {
        $this->cache->set(array('table', '1'), 'value');
        $this->assertTrue($this->cache->has(array('table', '1')));
    }

    public function testHasNotExistsStringKey()
    {
        $this->assertFalse($this->cache->has('somethingkey'));
    }

    public function testHasNotExistsArrayKey()
    {
        $this->assertFalse($this->cache->has(array('table', '10')));
    }

    public function testGetByArrayKey()
    {
        $this->cache->set(array('table', '2'), 'value');
        $this->assertEquals('value', $this->cache->get(array('table', '2')));
    }

    public function testGetByStringKeyWithDefaultValue()
    {
        $this->assertEquals('default value', $this->cache->get('somenotexistingkey', 'default value'));
    }

    public function testGetByArrayKeyWithDefaultValue()
    {
        $this->assertEquals(array('table', '3'), $this->cache->get('somenotexistingkey', array('table', '3')));
    }
}
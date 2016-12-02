<?php

/**
 * DbMetaSqliteTest
 */
class DbMetaSqliteTest extends PHPUnit_Framework_TestCase
{

    protected $meta;

    protected function setUp()
    {
        $this->meta = new \Odan\DbMeta\DbMetaSqlite();
        $this->meta->open(__DIR__ . '/meta.db');
        $this->meta->clear();
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(\Odan\DbMeta\DbMetaSqlite::class, $this->meta);
    }

    public function testHasStringKey()
    {
        $this->meta->set('key', 'value');
        $this->assertTrue($this->meta->has('key'));
    }

    public function testHasArrayKey()
    {
        $this->meta->set(array('table', '1'), 'value');
        $this->assertTrue($this->meta->has(array('table', '1')));
    }

    public function testHasNotExistsStringKey()
    {
        $this->assertFalse($this->meta->has('somethingkey'));
    }

    public function testHasNotExistsArrayKey()
    {
        $this->assertFalse($this->meta->has(array('table', '10')));
    }

    public function testGetByStringKey()
    {
        $this->meta->set('key', 'value');
        $this->assertEquals('value', $this->meta->get('key'));
    }

    public function testGetByArrayKey()
    {
        $this->meta->set(array('table', '2'), 'value');
        $this->assertEquals('value', $this->meta->get(array('table', '2')));
    }

    public function testGetByStringKeyWithDefaultValue()
    {
        $this->assertEquals('default value', $this->meta->get('somenotexistingkey', 'default value'));
    }

    public function testGetByArrayKeyWithDefaultValue()
    {
        $this->assertEquals(array('table', '3'), $this->meta->get('somenotexistingkey', array('table', '3')));
    }

}

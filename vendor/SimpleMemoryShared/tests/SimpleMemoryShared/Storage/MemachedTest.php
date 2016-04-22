<?php

namespace SimpleMemorySharedTest\Storage;

use PHPUnit_Framework_TestCase as TestCase;
use SimpleMemoryShared\Storage;

class MemachedTest extends TestCase
{
    protected $storage;

    public function setUp()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped('Memcache extension must be loaded.');
        }
        $this->storage = new Storage\Memcached(
            array(
                'host' => '127.0.0.1',
                'port' => 11211,
            )
        );
    }

    public function tearDown()
    {
        if(!$this->storage) {
            return;
        }
        $this->storage->clear();
        $this->storage->close();
    }
    
    public function testCannotHasWithoutAlloc()
    {
        $this->assertFalse($this->storage->has('custom-key'));
    }

    public function testCanWriteAndRead()
    {
        $has = $this->storage->has('custom-key');
        $this->assertEquals($has, false);

        $this->storage->write('custom-key', 'sample');
        $datas = $this->storage->read('custom-key');
        $this->assertEquals($datas, 'sample');

        $has = $this->storage->has('custom-key');
        $this->assertEquals($has, true);

        $this->storage->clear('custom-key');

        $has = $this->storage->has('custom-key');
        $this->assertEquals($has, false);
    }

    public function testCanCleanAll()
    {
        $this->storage->write('first', 'sample');
        $this->storage->write('second', 'sample');

        $has = $this->storage->has('first');
        $this->assertEquals($has, true);
        $has = $this->storage->has('second');
        $this->assertEquals($has, true);

        $this->storage->clear();

        $has = $this->storage->has('first');
        $this->assertEquals($has, false);
        $has = $this->storage->has('second');
        $this->assertEquals($has, false);
    }
}

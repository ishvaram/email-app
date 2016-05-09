<?php

/**
 * sudo memcached -d -u nobody -m 128 127.0.0.1 -p 11211 // to run memcached for tests
 */

namespace SimpleMemorySharedTest\Storage;

use PHPUnit_Framework_TestCase as TestCase;
use SimpleMemoryShared\Storage;

class BlocTest extends TestCase
{
    protected $storage;

    public function setUp()
    {
        $this->storage = new Storage\Bloc('V');
    }

    public function tearDown()
    {
        $this->storage->close();
    }
    
    public function testCannotHasWithoutAlloc()
    {
        $this->assertFalse($this->storage->has(1));
    }

    public function testCanWriteAndRead()
    {
        $this->storage->clear(3);
        $has = $this->storage->has(3);
        $this->assertEquals($has, false);

        $this->storage->write(3, 'sample');
        $datas = $this->storage->read(3);
        $this->assertEquals($datas, 'sample');

        $has = $this->storage->has(3);
        $this->assertEquals($has, true);
    }

    public function testCanWriteAndReadWithNumericKey()
    {
        $this->storage->write('1', 'foo');
        $datas = $this->storage->read('1');
        $this->assertEquals($datas, 'foo');
    }

    public function testCannotWriteAndReadWithStringKey()
    {
        $this->setExpectedException('SimpleMemoryShared\Storage\Exception\RuntimeException');
        $this->storage->write('custom-key', 'sample');
        $datas = $this->storage->read('custom-key');
        $this->assertEquals($datas, 'sample');
    }

    public function testCanWriteAndReadIntValue()
    {
        $this->storage->write('1', 12);
        $datas = $this->storage->read('1');
        $this->assertEquals('string', gettype($datas));
        $datas = (integer)$datas;
        $this->assertEquals($datas, 12);
    }

    public function testCanWriteAndReadBooleanValue()
    {
        $this->storage->write('1', true);
        $datas = $this->storage->read('1');
        $this->assertEquals('string', gettype($datas));
        $datas = (boolean)$datas;
        $this->assertEquals($datas, true);
    }
}

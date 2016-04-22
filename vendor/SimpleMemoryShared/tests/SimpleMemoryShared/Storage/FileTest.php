<?php

/**
 * sudo memcached -d -u nobody -m 128 127.0.0.1 -p 11211 // to run memcached for tests
 */

namespace SimpleMemorySharedTest\Storage;

use PHPUnit_Framework_TestCase as TestCase;
use SimpleMemoryShared\Storage;

class FileTest extends TestCase
{
    protected $storage;

    public function setUp()
    {
        $this->storage = new Storage\File(array('dir' => __DIR__ . '/../../tmp'));
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

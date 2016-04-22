<?php

namespace SimpleMemorySharedTest\Storage;

use PHPUnit_Framework_TestCase as TestCase;
use SimpleMemoryShared\Storage;
use Zend\Db\Adapter\Adapter;

class DbTest extends TestCase
{
    protected $storage;

    public function setUp()
    {
        $config = include __DIR__ . '/../../config.local.php';
        $adapter = new Adapter($config['db']);
        $config['db_storage']['adapter'] = $adapter;
        $this->storage = new Storage\Db($config['db_storage']);
    }

    public function testCanWriteAndRead()
    {
        $has = $this->storage->has('custom-key');
        $this->assertEquals($has, false);

        $success = $this->storage->write('custom-key', 'sample');
        $this->assertEquals($success, true);

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

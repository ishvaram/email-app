<?php

/*
 * This file is part of the SimpleMemoryShared package.
 * @copyright Copyright (c) 2012 Blanchon Vincent - France (http://developpeur-zend-framework.fr - blanchon.vincent@gmail.com)
 */

namespace SimpleMemoryShared;

use Zend\ServiceManager\AbstractPluginManager;

class StoragePluginManager extends AbstractPluginManager
{
    /**
     * Default set of storage
     *
     * @var array
     */
    protected $invokableClasses = array(
        'apc'           => 'SimpleMemoryShared\Storage\Apc',
        'db'            => 'SimpleMemoryShared\Storage\Db',
        'file'          => 'SimpleMemoryShared\Storage\File',
        //'file_ftp'    => 'SimpleMemoryShared\Storage\FileFtp',
        'memcached'     => 'SimpleMemoryShared\Storage\Memcached',
        //'redis'       => 'SimpleMemoryShared\Storage\Redis',
        'segment'       => 'SimpleMemoryShared\Storage\Segment',
        'bloc'          => 'SimpleMemoryShared\Storage\Bloc',
        'session'       => 'SimpleMemoryShared\Storage\Session',
        'zendshmcache'  => 'SimpleMemoryShared\Storage\ZendShmCache',
        'zendshm'       => 'SimpleMemoryShared\Storage\ZendShmCache',
        'zenddiskcache' => 'SimpleMemoryShared\Storage\ZendDiskCache',
        'zenddisk'      => 'SimpleMemoryShared\Storage\ZendDiskCache',
    );

    /**
     * Validate the plugin
     *
     * Checks that the adapter loaded is an instance
     * of Storage\StorageInterfaceInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Storage\StorageInterface) {
            // we're okay
            return;
        }

        throw new Storage\Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Storage\StorageInterfaceInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}

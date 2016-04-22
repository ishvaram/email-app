<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Emailapp;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Emailapp\Model\Emailapp;
use Emailapp\Model\EmailappTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	// Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Emailapp\Model\EmailappTable' =>  function($sm) {
                    $tableGateway = $sm->get('EmailappTableGateway');
                    $table = new EmailappTable($tableGateway);
                    return $table;
                },
				
                'EmailappTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Emailapp());
		    return new TableGateway('email_list', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

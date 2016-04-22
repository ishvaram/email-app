<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'email-template' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(                        
			'controller' => 'EmailTemplate\Controller\EmailTemplate',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /EmailTemplate/:controller/:action            
            'email-template' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/email-template[/][:action][/:pkey]',
                    'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'pkey'     => '[0-9]+',
                     ),
                    'defaults' => array(
                        'controller' => 'EmailTemplate\Controller\EmailTemplate',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
	
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    
    'controllers' => array(
        'invokables' => array(            
            'EmailTemplate\Controller\EmailTemplate' => 'EmailTemplate\Controller\EmailTemplateController'
        ),
    ),
	
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(            
            'EmailTemplate/index/index' => __DIR__ . '/../view/EmailTemplate/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
 
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

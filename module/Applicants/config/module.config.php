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
            'applicants' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/applicants[/][:action][/:pkey][/:status][/:tab][/:doc_pkey]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',  
                        'pkey'     => '[0-9]+',
                        'action' => '[a-zA-Z][a-zA-Z_-]*', 
                        'tab'     => '[0-9]+',
                        'doc_pkey'  => '[0-9]+', 
                    ),
                    'defaults' => array(
                        'controller' => 'Applicants\Controller\Applicants',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
		
    'controllers' => array(
        'invokables' => array(            
            'Applicants\Controller\Applicants' => 'Applicants\Controller\ApplicantsController'
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
       	
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(            
            'freeze/freeze/index' => __DIR__ . '/../view/applicants/applicants/index.phtml',
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
    
    //Breadcrumbs
     'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'applicants',
            ),
            array(
                'label' => 'Applicants',
                'route' => 'applicants',
                'pages' => array(                    
                    array(
                        'label' => 'View',
                        'route' => 'applicants',
                        'action' => 'edit',
                    ),                    
                ),
            ),
        ),
    ), 
);

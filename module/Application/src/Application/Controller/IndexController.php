<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    const ROUTE_APPLICANT    = 'applicants';
    const SA_ROUTE_APPLICANT    = 'emailapp';
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {		
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        else
        {
            if($this->getRole() == 'admin') {
                return $this->redirect()->toRoute(static::ROUTE_APPLICANT);
            } 
            else {
                return $this->redirect()->toRoute(static::SA_ROUTE_APPLICANT);
            }
        }    
        return new ViewModel();
    }
}

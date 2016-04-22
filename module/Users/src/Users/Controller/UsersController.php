<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Users\Model\UsersTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Users\Model\Users;          
use Users\Form\UsersForm;  
use Users\Form\UsersEditForm;  
use Users\Form\SearchForm;  

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class UsersController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    protected $UsersTable;
    protected $userService;
    public function indexAction()
    {		
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        //Auth User Identity
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if($auth->hasIdentity())
        {            
            $logged_user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        }
        
        $page = $this->params()->fromQuery('page', 1); 
        $search = $this->params()->fromQuery('search', '');
        $result = $this->getUsersTable()->searchBy($_REQUEST, $search);
        $itemsPerPage = 25;
        $paginator = array();
        $rowCount = 0;
        if(count($result)>0) {
            $result->current();
            $paginator = new Paginator(new paginatorIterator($result));
            $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(9);
            $rowCount = $paginator->getTotalItemCount();
        }
        
        $form = new SearchForm();
        $view = new ViewModel(array(
                    'form' => $form,
                    'message' => 'Users',		
                    'users' => $paginator,
                    'rowCount' => $rowCount,
                    'search' => $search,
                ));		
       return $view;
    }

    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->userService;
    }

    public function getUsersTable()
    {
        if (!$this->UsersTable) {
            $sm = $this->getServiceLocator();
            $this->UsersTable = $sm->get('Users\Model\UsersTable');
        }
        return $this->UsersTable;
    }
    //Add Users
    public function addAction()
    {		
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new UsersForm($dbAdapter);
        $state = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $Users = new Users();
            $form->setInputFilter($Users->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $Users->exchangeArray($form->getData());								
                $email = $Users->email;
                $username = $Users->username;
                $service = $this->getUserService();
                $validate_email = $service->findByEmail($email);
                $validate_username = $service->findByUsername($username);
                if(!$validate_email && !$validate_username)
                {
                    $this->getUsersTable()->saveUsers($Users);
                    $state = true;
                }				
                else
                {
                    return array(
                            'status' => false,
                            'form' => $form,
                    );
                }
                // Redirect to list of users         
                $this->flashMessenger()->addSuccessMessage('User added successfully');
                return $this->redirect()->toRoute('users');
            }
        }
        return array('form' => $form, 'status' => $state);
    }
    //Edit Users
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);		
        if (!$id) {
            return $this->redirect()->toRoute('users', array(
                'action' => 'add'
            ));
        }
        $Users = $this->getUsersTable()->getUsers($id);		
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new UsersEditForm($dbAdapter);
        $Users->password = "";
        $form->bind($Users);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($Users->getInputFilter());
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getUsersTable()->saveUsers($form->getData());
                // Redirect to list of albums
                $this->flashMessenger()->addSuccessMessage('User updated successfully');
                return $this->redirect()->toRoute('users');
            }
        }
        //print_r($Users); exit;
        return array(
            'id' => $id,
            'form' => $form,
            'users' => $Users,
        );
    }

    public function viewAction()
    {	
        $id = (int) $this->params()->fromRoute('id', 0);		
        if (!$id) {
            return $this->redirect()->toRoute('users', array(
                'action' => 'add'
            ));
        }
        $view = new ViewModel(array(            
               'user' => $this->getUsersTable()->getUsers($id),
        ));		
        return $view;
    }

    //Delete Users
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getUsersTable()->deleteUsers($id);
            }
            // Redirect to list of albums
            $this->flashMessenger()->addSuccessMessage('User deleted successfully');
            return $this->redirect()->toRoute('users');
        }
        return array(
            'id'    => $id,
            'users' => $this->getUsersTable()->getUsers($id)
        );
    }
	
}

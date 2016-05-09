<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace EmailTemplate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmailTemplate\Model\EmailTemplate;  
use EmailTemplate\Form\EmailTemplateForm;  

class EmailTemplateController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    protected $Table;
    protected $userService;
	 
    public function indexAction()
    {		
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }        
        $view = new ViewModel(array(         
            'result' => $this->getTable()->fetchAll(),
        ));
	return $view;
    }
    public function getTable()
    {
        if (!$this->Table) {
            $sm = $this->getServiceLocator();
            $this->Table = $sm->get('EmailTemplate\Model\EmailTemplateTable');
        }
        return $this->Table;
    }
    public function addAction()
    {		
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new EmailTemplateForm($dbAdapter);        
        $request = $this->getRequest();        
        if ($request->isPost()) {            
            $template = new EmailTemplate();
            $form->setInputFilter($template->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $template->exchangeArray($form->getData());
                $this->getTable()->saveTemplate($template);                
                $this->flashMessenger()->addSuccessMessage('Email Template created successfully');
                return $this->redirect()->toRoute('email-template');
            } else {
                print_r($form->getMessages());
            }
        }
        return array('form' => $form);
    }    
    public function editAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $pkey = (int) $this->params()->fromRoute('pkey', 0);		
        if (!$pkey) {
            return $this->redirect()->toRoute('email-template', array(
                'action' => 'add'
            ));
        }
        $emailTemplate = new EmailTemplate();
        $template = $this->getTable()->getTemplate($pkey);		
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new EmailTemplateForm($dbAdapter);
        $form->bind($template);                
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($emailTemplate->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {                
                $emailTemplate->exchangeArray($request->getPost());                                
                $this->getTable()->saveTemplate($emailTemplate); 
                $this->flashMessenger()->addSuccessMessage('Email Template updated successfully');
                return $this->redirect()->toRoute('email-template');
            }
        }
        return array(
            'pkey' => $pkey,
            'form' => $form,      
            'template' => $template,
        );
    }
    public function viewAction()
    {	
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $pkey = (int) $this->params()->fromRoute('pkey', 0);		
        if (!$pkey) {
            return $this->redirect()->toRoute('email-template', array(
                'action' => 'add'
            ));
        }
        $view = new ViewModel(array(            
            'result' => $this->getTable()->getTemplate($pkey),
        ));		
        return $view;
    }
    public function deleteAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        if (!$pkey) {
            return $this->redirect()->toRoute('email-template');
        }
        $template = $this->getTable()->getTemplate($pkey);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {                                
                $pkey = (int) $request->getPost('pkey');
                $this->getTable()->deleteTemplate($pkey);
                $this->flashMessenger()->addSuccessMessage('Email Template deleted successfully');
            }            
            return $this->redirect()->toRoute('email-template');
        }
        return array(
            'pkey'    => $pkey,
            'result' => $template,
        );
    }
    
    public function getJobListAction()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();          
            $univ = $data->univ;        
            $job = $this->getTable()->getJobList($univ);        
            $job_json = json_encode($job);
            $response->setContent($job_json);  
        }    
        return $response;
    }
	
    public function getSelectedJobListAction(){
        $content = "<option value=''>Select Job</option>";
        $response = $this->getResponse();
        $request = $this->getRequest();                
        if ($request->isPost()) {
            $data = $request->getPost(); 
            $univ = $data->univ;  
            $sel_job = explode(",", $data->job);
            $job = $this->getTable()->getJobList($univ);            
            if(count($job)>0){
                foreach($job as $key => $value){
                    $selected = '';
                    if(in_array($key, $sel_job)){
                        $selected = 'selected="selected"';
                    }
                    $content .= "<option value='".$key."' ".$selected.">".$value."</option>";
                }
            }            
        }
        $response->setContent($content);    
        return $response;
    }
}

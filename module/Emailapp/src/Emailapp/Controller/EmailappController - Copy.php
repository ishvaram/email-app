<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Emailapp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Emailapp\Model\EmailappTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Emailapp\Model\Emailapp;          
use Emailapp\Form\EmailappForm;  
use Emailapp\Form\EmailappEditForm;  
use Emailapp\Form\EmailappUploadForm;

use Applicants\Controller\Plugin\EmailAlert;

class EmailappController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    protected $EmailappTable;
    protected $emailappsService;
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
        $view = new ViewModel(array(
                    'message' => 'Emailapp',		
                    'emailapp' => $this->getEmailappTable()->searchBy($_REQUEST),
                ));		
       return $view;
    }

    public function getEmailappsService()
    {
        if (!$this->emailappsService) {
            $this->emailappsService = $this->getServiceLocator()->get('zfcuser_user_mapper');
        }
        return $this->emailappsService;
    }

    public function getEmailappTable()
    {
        if (!$this->EmailappTable) {
            $sm = $this->getServiceLocator();
            $this->EmailappTable = $sm->get('Emailapp\Model\EmailappTable');
        }
        return $this->EmailappTable;
    }
   
    //Add Emailapp
    public function addAction()
    {		
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new EmailappForm($dbAdapter);
        $state = null;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $Emailapp = new Emailapp();
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $Emailapp->exchangeArray($form->getData());								
                $this->getEmailappTable()->saveEmailapp($Emailapp);
                $state = true;
                // Redirect to list of Emailapp   
                $this->flashMessenger()->addSuccessMessage('Emailapp added successfully');
                return $this->redirect()->toRoute('emailapp');
            }
        }
        return array('form' => $form, 'status' => $state);
    }
    
    //Edit Emailapp
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);		
        if (!$id) {
            return $this->redirect()->toRoute('emailapp', array(
                'action' => 'add'
            ));
        }
        $Emailapp = $this->getEmailappTable()->getEmailapp($id);		
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new EmailappEditForm($dbAdapter);
        $form->bind($Emailapp);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
           if ($form->isValid()) {
                $this->getEmailappTable()->saveEmailapp($form->getData());
                // Redirect to list of Emailapp
                $this->flashMessenger()->addSuccessMessage('Emailapp updated successfully');
                return $this->redirect()->toRoute('emailapp');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
            'emailapp' => $Emailapp,
        );
    }
   
    public function viewAction()
    {	
        $id = (int) $this->params()->fromRoute('id', 0);		
        if (!$id) {
            return $this->redirect()->toRoute('emailapp', array(
                'action' => 'add'
            ));
        }
        $view = new ViewModel(array(            
               'emailapps' => $this->getEmailappTable()->getEmailapp($id),
        ));	
        return $view;
    }
   
    //Delete Emailapp
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('emailapp');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                //echo $id; exit;
                $deletestatus = $this->getEmailappTable()->deleteEmailapp($id);
                // Redirect to list of Emailapp
                if($deletestatus == 'error')
                {
                    $this->flashMessenger()->addErrorMessage('Can not delete this record, since other records are depends on it.');
                }
                else
                {
                    $this->flashMessenger()->addSuccessMessage('Emailapp deleted successfully');
                }
            }
            
            return $this->redirect()->toRoute('emailapp');
        }
        return array(
            'id'    => $id,
            'emailapp' => $this->getEmailappTable()->getEmailapp($id)
        );
    }
    
    // Send Mail to all Users
    public function sendmailAction() {
       //$sendStatus = $this->getEmailappTable()->SendEmailapp();
       $Emaillist = $this->getEmailappTable()->searchBy($_REQUEST);
       foreach ($Emaillist as $res)
        {
            $to_name = $res['name'];
            $to_email = $res['email'];
            $email_template = $res['content'];
            $triggerMail = new EmailAlert(array());
            $mail_send = $triggerMail->sendBulkEmail($to_email,$to_name,$email_template);
            $updateDate = $this->getEmailappTable()->UpdateEmailapp($res['id']);
        }
       $this->flashMessenger()->addSuccessMessage('Mail Sent successfully');
       return $this->redirect()->toRoute('emailapp');
    }
    
    
    //Import Action
    public function importAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new EmailappUploadForm($dbAdapter);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $Emailapp = new Emailapp();
            $form->setData($request->getPost());
            if ($form->isValid())
            {
                $Emailapp->exchangeArray($form->getData());
                $nonFile = $request->getPost()->toArray();
                $File    = $this->params()->fromFiles('fileupload');
                $data = array_merge(
                $nonFile,
                array('fileupload'=> $File['name'])
                );
                //echo '<pre>';
                              //     print_r($File['content']);
                               // echo '</pre>';
                              // exit;
                //$adapter = new \Zend\File\Transfer\Adapter\Http(); 
               // $adapter->setDestination($_SERVER['DOCUMENT_ROOT'].'/files');
               // $fileLocation = $_SERVER['DOCUMENT_ROOT'].'/files/'.$File['name'];
                //echo $fileLocation;
                //if ($adapter->receive($File['name'])) {
                   // echo 'upload '.$profile->fileupload;
                    $total_error_cnt = 0;                    
                    $error_array = array();
                    $row = 0;
                    $str = file_get_contents($_FILES["fileupload"]["tmp_name"]);
                    echo '<pre>';
                    print_r($str);
                    echo '</pre>';
                    exit;
                    echo fopen($File['name'], "r");
                    if (($handle = fopen($File['name'], "r")) !== FALSE) {
                        echo 'seefe';
                        while (($data = fgetcsv($handle, '', ",")) !== FALSE) {
                            $num = count($data);
                            echo $num; 
                            $row++;                            
                            if($row != 1)
                            {
                                for ($c=1; $c < $num; $c++) {
                                    $EmailappStart = array(
                                    'id' => '',
                                    'name' => $data[0],
                                    'email' => $data[1],
                                    'content' => $data[2],
                                    'import_date' => date('Y-m-d H:i:s'), 
                                    );
                                    
                                }
                                $Emailapp = array(
                                    'id' => '',
                                    'name' => $data[0],
                                    'email' => $data[1],
                                    'content' => $data[2],
                                    'import_date' => date('Y-m-d H:i:s'), 
                                    );  
                               // echo '<pre>';
                               // print_r($Emailapp);
                               // echo '</pre>';
                                //exit;
                                $this->getEmailappTable()->saveEmailappImport($Emailapp);
                            }
                        }
                        exit;
                        fclose($handle);
                        
                    }
                //}
            }
            //Redirect to list of Emailapp 
            $this->flashMessenger()->addSuccessMessage('Email list imported successfully');
            return $this->redirect()->toRoute('emailapp');
        }
        return array('form' => $form);
   }
   
   
}

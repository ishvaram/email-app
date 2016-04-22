<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Applicants\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Applicants\Form\SearchForm;  
use Applicants\Form\EditForm;  
//use Applicants\Form\EditWilkesForm;
//use Applicants\Form\EditAdlerForm;

use Applicants\Form\ApplicationForm;
use Applicants\Model\Applicants;
use Zend\Session\Container;
use TCPDF;
use Applicants\Controller\Plugin\UploadHandler;
use Applicants\Controller\Plugin\EmailAlert;


use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;

class ApplicantsController extends AbstractActionController
{
    const ROUTE_LOGIN        = 'zfcuser/login';
    public $list = array();	
    protected $table;
    protected $userService;

    public function indexAction()
    {		
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }	
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $request = $this->getRequest();
        $post = $request->getPost();
        
        //Call Send Mail Function
        //$emailAlert = new EmailAlert();
        //$emailAlert->sendEmail();
        
        //SET SESSION
        $applicant_session = new Container('applicant_session');
        if(count($post)>0)
        {
            if(isset($post->filter_type) && $post->filter_type=='load-filter')
            {
                $load_filter_id = $post->availabe_filter;
                if($load_filter_id>0)
                {
                    $load_filters = $this->loadFilter($load_filter_id);
                    if(count($load_filters)>0)
                    {
                        $filter_session = $load_filters;
                    }    
                }
            }
            else if(isset($post->filter_type) && $post->filter_type=='clear-filter')
            {
                $filter_session = '';
            }    
            else
            {
                $filter_session = $post;
            }
            // if it's slipped through the cracks, set the post
            if(!isset($filter_session) && isset($post)){
                $filter_session = $post;
            }
            $applicant_session->post = $filter_session;
        }         
        
        $status = $this->params()->fromRoute('status', '');        
        $form = new SearchForm($dbAdapter,$applicant_session->post);
        //Get Result Data        
        //$result = $this->getTable()->getSearchResult($applicant_session->post,$status);
        $page = $this->params()->fromQuery('page', 1); 
        $search = $this->params()->fromQuery('search', '');         
        $result = $this->getTable()->getSearchResult($applicant_session->post,$status,$search);
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
        
        $enabledColumns = $this->getTable()->getEnabledColumns();
        $columnsList = $enabledColumns['list'];
        //$columnsConfig = $enabledColumns['data'];
        
        $clear_filter_array = $this->clearFilterHtmlArray($applicant_session->post);
        //Applicants applied for multiple jobs from same school
        $alreadyApplied = '';
        if(count($result)>0)
        {
            $alreadyApplied = $this->getTable()->getApplicantMultipleJobs($applicant_session->post,$status);
        }
        
        $view = new ViewModel(array(
            'form' => $form,               
            'result' => $paginator,
            'clear_filter_array' => $clear_filter_array,
            'labelList' => $columnsList,    
            'alreadyApplied' => $alreadyApplied,
            'rowCount' =>  $rowCount,
            'search' => $search,
         ));
        return $view;				
    }
    public function loadFilter($load_filter_id)
    {
        $temp_array = array();       
        $filter_array = array();
        $filter_array[] = array('name'=>'university','table'=>'applicantfilteruniversity','column_name'=>'Partners_pkey');
        $filter_array[] = array('name'=>'state','table'=>'applicantfilterstate','column_name'=>'StateListLOV_pkey');
        $filter_array[] = array('name'=>'highest_degree','table'=>'applicantfilterdegree','column_name'=>'EducationListEnum_pkey');
        $filter_array[] = array('name'=>'instructor_type','table'=>'applicantfilterinstructortype','column_name'=>'InstructorTypeLOV_pkey');
        $filter_array[] = array('name'=>'application_status','table'=>'applicantfilterstatus','column_name'=>'AppStatusLOV_pkey');
        $filter_array[] = array('name'=>'course','table'=>'applicantfilterqualifiedtoteach','column_name'=>'CourseDef_pkey');
        foreach($filter_array as $filter)
        {    
            $result = $this->getTable()->getApplicantFilterTable($load_filter_id,$filter['table'],$filter['column_name']);
            if(count($result)>0)
            {
                $temp_array[$filter['name']] = $result;
            }    
        }
        $date_filter = $this->getTable()->getApplicantFilterTableDate($load_filter_id);        
        if(count($date_filter)>0)
        {   
            $temp_array['application_from_date'] = $date_filter['startDate'];
            $temp_array['application_to_date'] = $date_filter['endDate'];
        }        
        return $temp_array;
    }        
    public function clearFilterHtmlArray($applicant_session_post)
    {
        $html_array = array();
        if(isset($applicant_session_post['university']))
        {
            foreach($applicant_session_post['university'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('partners',$pkey);
                foreach($title as $res)
                {   
                    $html_array['university'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }    
        }
        if(isset($applicant_session_post['job']))
        {
            foreach($applicant_session_post['job'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('joblisting',$pkey);
                foreach($title as $res)
                {   
                    $html_array['job'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }    
        }
        if(isset($applicant_session_post['instructor_type']))
        {
            foreach($applicant_session_post['instructor_type'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('instructortypelov',$pkey);
                foreach($title as $res)
                {   
                    $html_array['instructor_type'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }
        }
        if(isset($applicant_session_post['highest_degree']))
        {
            foreach($applicant_session_post['highest_degree'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('educationlistenum',$pkey);
                foreach($title as $res)
                {   
                    $html_array['highest_degree'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }
        }
        if(isset($applicant_session_post['state']))
        {
            foreach($applicant_session_post['state'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('statelistlov',$pkey);
                foreach($title as $res)
                {   
                    $html_array['state'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }
        }
        if(isset($applicant_session_post['application_status']))
        {
            foreach($applicant_session_post['application_status'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitle('appstatuslov',$pkey);
                foreach($title as $res)
                {   
                    $html_array['application_status'][] = array('id'=>$pkey, 'title'=>$res->name);
                }
            }
        }
        if(isset($applicant_session_post['course']))
        {
            foreach($applicant_session_post['course'] as $pkey)
            {
                $title = $this->getTable()->getFilterTitleCourse('coursedef',$pkey);                
                foreach($title as $res)
                {   
                    $html_array['course'][] = array('id'=>$pkey, 'title'=>$res->group_code.' / '.$res->code);
                }
            }
        }
        if(!empty($applicant_session_post['application_from_date']) && !empty($applicant_session_post['application_to_date']) )
        {
            $html_array['application_from_date'] = date('m/d/Y', strtotime($applicant_session_post['application_from_date']));
            $html_array['application_to_date'] = date('m/d/Y', strtotime($applicant_session_post['application_to_date']));
        } 
        return $html_array;
    }        

    public function editAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }        
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        $doc_pkey = (int) $this->params()->fromRoute('doc_pkey', 0);
        if (!$pkey) {
            return $this->redirect()->toRoute('applicants', array(
                'action' => 'index'
            ));
        }
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');        
        $job = $this->getTable()->getJobDetailByPkey($pkey);    
        
        //Load form for stu and cu
        if($job->domainName=='stu'){
            $applicant = $this->getTable()->getApplicant($pkey);
            $form = new EditForm($dbAdapter,$applicant); 
            $form->bind($applicant);   
            if(strtolower($job->domainName)!='adler'){
                    $form->get('partnersName')->setValue($partnersName); 
            }
            $ApplicantsModel = new Applicants();
            $request = $this->getRequest(); 
            if ($request->isPost()) {
                $form->setInputFilter($ApplicantsModel->getInputFilter());
                $form->setData($request->getPost()); 
                if($form->isValid()) {              
                    $ApplicantsModel->exchangeArray($form->getData());                
                    if(strtolower($domainName)=='wilkes'){
                        $affected_count = $this->getTable()->saveWilkesApplicant($form->getData(),$request->getPost());                
                    } else if(strtolower($domainName)=='adler'){                    
                        $affected_count = $this->getTable()->saveAdlerApplicant($request->getPost());
                    } else {                    
                        $affected_count = $this->getTable()->saveApplicant($form->getData());
                    }
                    if($affected_count>0)
                    {
                        //Insert audittran
                        $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
                        $audit_array = array();
                        $audit_array['Newapplication_pkey'] = $pkey;
                        $audit_array['role'] = $this->getRole();
                        $audit_array['user'] = $user_email;
                        $audit_array['date'] = date('Y-m-d');     
                        $audit_array['time'] = date('G:i:s');
                        $audit_array['businessEventStatus'] = 'Success';
                        $audit_array['message'] = 'Success';
                        $audit_array['BusinessEventTypeEnum_pkey'] = 39;                
                        $this->getTable()->insertAuditApplicantHistory($audit_array);                   
                    }    
                    $this->flashMessenger()->addSuccessMessage('Profile updated successfully');
                    return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey));                
                }       
            }
            //Get Documents
            $documents = $this->getTable()->getDocumentsByApplicant($pkey);
            $licensestypeapp = $this->getTable()->getLicensestypeapp($pkey);        
            $licensestypelov = $this->getTable()->getLicenseTypeList();
            $issuingagencylov = $this->getTable()->getIssuingAgencyTypeList();
            $certificationtypelov = $this->getTable()->getCertificateTypeList();
            $doc_file_name = '';
            if($doc_pkey>0){
                $doc_file_name = $this->getTable()->getDocumentUpload($doc_pkey);
            }
            //Get Courses List to add 
            $coursesList = $this->getTable()->getcoursesList($pkey);
            //Get Courses Qualified
            $coursesQualified = $this->getTable()->getcoursesQualifiedByApplicant($pkey);
            //Get Application Status        
            $currentStatus = $this->getTable()->getCurrentStatus($pkey);        
            $appStatus = $this->getTable()->appStatusChangeHistory($pkey); 
            $appStatusList = $this->getTable()->getApplicationStatusList();
            //Audit History
            $auditHistory = $this->getTable()->getApplicationAuditHistory($pkey);
            $view = new ViewModel(array(
                'pkey' => $pkey,
                'applicant' => $applicant,
                'form' => $form,
                'partnersName' => $partnersName,            
                'documents' => $documents,
                'coursesList' => $coursesList,
                'coursesQualified' => $coursesQualified,
                'currentStatus' => $currentStatus,
                'appStatus' => $appStatus,
                'appStatusList' => $appStatusList,
                'wilkesemployerinfo' => $wilkesemployerinfo,  
                'auditHistory' => $auditHistory,
                'doc_pkey' => $doc_pkey,
                'licensestypeapp' => $licensestypeapp,
                'licensestypelov'=> $licensestypelov,
                'issuingagencylov'=> $issuingagencylov,
                'certificationtypelov'=> $certificationtypelov,
                'doc_file_name' => $doc_file_name,
            ));
            $view->setTemplate('applicants/applicants/edit-stu');
            return $view;
        }
        
        //Load form for other colleges
        $form = new ApplicationForm($dbAdapter,$job->domainName);
        $data = $this->getTable()->getApplicantByID($pkey);       
        $applicant = $this->getTable()->getApplicant($pkey);
        $multiple_column_array = array();
        foreach ($form->getElements() as $element) { 
            $ele = $element->getName();         
            $ele_replace = str_replace("[]","",$ele);
            foreach($data as $key => $value){
                if($key==$ele_replace){                    
                    if(strpos($ele, '[]') !== false ) {                                          
                        $array_value = json_decode($value);                        
                        $form->get($ele)->setValue($array_value[0]);                        
                        unset($array_value[0]);                        
                        //set multiple options..
                        $multiple_column_array[$ele] = array('count'=>count($array_value),'values'=>$array_value, 'type'=>$element->getAttribute('type'));
                    } else {                        
                        $array_value = json_decode($value);
                        if(is_array($array_value)){                            
                            $form->get($key)->setValue($array_value[0]);    
                        } else {
                            $form->get($key)->setValue($value);
                        }
                        //$form->get($key)->setValue($value);
                    }
                }
            }
        }
        
        $request = $this->getRequest(); 
        if ($request->isPost()) {            
            $newapplication = new Applicants();
            $form->setInputFilter($newapplication->getInputFilter()); 
            //Remove form input filter for not required
            foreach ($form->getElements() as $element) { 
                $ele = $element->getName();            
                $ele = str_replace('[]','',$ele);
                if ($element->hasAttribute('required')) {               
                   $required = $element->getAttribute('required');
                    if($required==''){
                       $form->getInputFilter()->remove($ele);
                    }     
                    //Remove Filter for Array
                    if(is_array($request->getPost($ele))){                        
                        $form->getInputFilter()->remove($ele.'[]');
                    }
                    //Remove Filter for Radio,Checkbox
                    if($element->getAttribute('type')=='radio' || $element->getAttribute('type')=='checkbox'){
                        $form->getInputFilter()->remove($ele);
                    }
                }
            }       
            if($request->getPost()->get('SocialSecurityNo1')!=''){
                $request->getPost()->set('S_SocialSecurityNo', $request->getPost()->get('SocialSecurityNo1'));
            }
            $form->setData($request->getPost());            
            if($form->isValid()) {                           
                $newapplication->exchangeArray($form->getData());                      
                $insertdata = array();
                $post = $request->getPost();
                if(count($post)>0){
                    foreach($post as $key => $value){
                        if($value==''){
                            $insertdata[$key] = NULL;
                        } else {
                            $insertdata[$key] = $value;
                        }
                        //Add-More column, saved in same column to avoid multiple tables
                        if(is_array($value)){                                 
                            $insertdata[$key] = json_encode(array_values($value));
                        }
                    }
                }   
                
                unset($insertdata['files']);
                unset($insertdata['file_type']);  
                unset($insertdata['SocialSecurityNo1']);
                $insertdata['is_saved'] = 0;
                $affected_count = $this->getTable()->save($insertdata);  
                if($affected_count>0)
                {
                    //Insert audittran
                    $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
                    $audit_array = array();
                    $audit_array['Newapplication_pkey'] = $pkey;
                    $audit_array['role'] = $this->getRole();
                    $audit_array['user'] = $user_email;
                    $audit_array['date'] = date('Y-m-d');     
                    $audit_array['time'] = date('G:i:s');
                    $audit_array['businessEventStatus'] = 'Success';
                    $audit_array['message'] = 'Success';
                    $audit_array['BusinessEventTypeEnum_pkey'] = 39;                
                    $this->getTable()->insertAuditApplicantHistory($audit_array);                   
                }    
                $this->flashMessenger()->addSuccessMessage('Profile updated successfully');
                return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey));                               
            } 
        }
        
        //Get Documents
        $documents = $this->getTable()->getDocumentsByApplicant($pkey);
        $licensestypeapp = $this->getTable()->getLicensestypeapp($pkey);        
        $licensestypelov = $this->getTable()->getLicenseTypeList();
        $issuingagencylov = $this->getTable()->getIssuingAgencyTypeList();
        $certificationtypelov = $this->getTable()->getCertificateTypeList();
        $doc_file_name = '';
        if($doc_pkey>0){
            $doc_file_name = $this->getTable()->getDocumentUpload($doc_pkey);
        }
        //Get Courses List to add 
        $coursesList = $this->getTable()->getcoursesList($pkey);
        //Get Courses Qualified
        $coursesQualified = $this->getTable()->getcoursesQualifiedByApplicant($pkey);
        //Get Application Status        
        $currentStatus = $this->getTable()->getCurrentStatus($pkey);        
        $appStatus = $this->getTable()->appStatusChangeHistory($pkey); 
        $appStatusList = $this->getTable()->getApplicationStatusList();
        //Audit History
        $auditHistory = $this->getTable()->getApplicationAuditHistory($pkey);
        
        $view = new ViewModel(array(            
            'job' => $job,
            'form' => $form,
            'pkey' => $pkey,        
            'applicant' => $applicant,
            'documents' => $documents,
            'coursesList' => $coursesList,
            'coursesQualified' => $coursesQualified,
            'currentStatus' => $currentStatus,
            'appStatus' => $appStatus,
            'appStatusList' => $appStatusList,              
            'auditHistory' => $auditHistory,
            'doc_pkey' => $doc_pkey,
            'licensestypeapp' => $licensestypeapp,
            'licensestypelov'=> $licensestypelov,
            'issuingagencylov'=> $issuingagencylov,
            'certificationtypelov'=> $certificationtypelov,
            'doc_file_name' => $doc_file_name,
            'multiple_column_array' => $multiple_column_array,
        ));
        
        return $view;
    }


    public function editAdditionalAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }        
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        if (!$pkey) {
            return $this->redirect()->toRoute('applicants', array(
                'action' => 'index'
            ));
        }
        $applicant = $this->getTable()->getApplicant($pkey);
        $additional_data_array = array();
        $additional_data_array[] = array('name'=>'additional_university','value'=>$applicant->additional_university,'business_id'=>'144');
        $additional_data_array[] = array('name'=>'additional_profit_nonprofit','value'=>$applicant->additional_profit_nonprofit,'business_id'=>'145');
        $additional_data_array[] = array('name'=>'additional_highest_degree','value'=>$applicant->additional_highest_degree,'business_id'=>'146');
        $additional_data_array[] = array('name'=>'additional_payrate','value'=>$applicant->additional_payrate,'business_id'=>'147');
        $additional_data_array[] = array('name'=>'additional_university_email','value'=>$applicant->additional_university_email,'business_id'=>'148');
        $additional_data_array[] = array('name'=>'additional_education_exp','value'=>$applicant->additional_education_exp,'business_id'=>'149');
        $additional_data_array[] = array('name'=>'additional_online_exp','value'=>$applicant->additional_online_exp,'business_id'=>'150');
        $additional_data_array[] = array('name'=>'additional_start_date','value'=>$applicant->additional_start_date,'business_id'=>'151');
        $additional_data_array[] = array('name'=>'additional_job_type','value'=>$applicant->additional_job_type,'business_id'=>'152');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();            
            $affected_count = $this->getTable()->saveAdditionalApplicant($post);            
            if($affected_count>0){
                //Insert audittran
                $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
                //Insert Additional Audit
                foreach ($additional_data_array as $additional_data){
                    if($additional_data['name']=='additional_start_date'){
                        if(!empty($post->$additional_data['name'])){
                            $to_value = date('Y-m-d',  strtotime($post->$additional_data['name']));
                        }                                
                    } else {
                        $to_value = $post->$additional_data['name'];
                    }                        
                    $from_value = $additional_data['value'];
                    if($from_value!=$to_value){                     
                        $audit_array = array();
                        $audit_array['Newapplication_pkey'] = $pkey;
                        $audit_array['role'] = $this->getRole();
                        $audit_array['user'] = $user_email;
                        $audit_array['date'] = date('Y-m-d');     
                        $audit_array['time'] = date('G:i:s');
                        $audit_array['businessEventStatus'] = 'Success';
                        $audit_array['message'] = 'Success';
                        $audit_array['BusinessEventTypeEnum_pkey'] = $additional_data['business_id']; 
                        $audit_array['fromValue'] = $from_value;
                        $audit_array['toValue'] = $to_value;
                        $this->getTable()->insertAuditApplicantHistory($audit_array);
                    }
                }
            }            
            $this->flashMessenger()->addSuccessMessage('Additional Information updated successfully');
            return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey, 'tab'=>5));
        }        
    }        
    public function editNotesAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }        
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        if (!$pkey) {
            return $this->redirect()->toRoute('applicants', array(
                'action' => 'index'
            ));
        }
        $applicant = $this->getTable()->getApplicant($pkey);
        $additional_data_array = array();
        $additional_data_array[] = array('name'=>'notes','value'=>$applicant->notes,'business_id'=>'153');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();            
            $affected_count = $this->getTable()->saveNotesApplicant($post);            
            if($affected_count>0){                
                //Insert audittran
                $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
                //Insert Additional Audit
                foreach ($additional_data_array as $additional_data){
                    $to_value = $post->$additional_data['name'];                                             
                    $from_value = $additional_data['value'];                    
                    if($from_value!=$to_value){                         
                        $audit_array = array();
                        $audit_array['Newapplication_pkey'] = $pkey;
                        $audit_array['role'] = $this->getRole();
                        $audit_array['user'] = $user_email;
                        $audit_array['date'] = date('Y-m-d');     
                        $audit_array['time'] = date('G:i:s');
                        $audit_array['businessEventStatus'] = 'Success';
                        $audit_array['message'] = 'Success';
                        $audit_array['BusinessEventTypeEnum_pkey'] = $additional_data['business_id']; 
                        $audit_array['fromValue'] = $from_value;
                        $audit_array['toValue'] = $to_value;
                        $this->getTable()->insertAuditApplicantHistory($audit_array);
                    }
                }
            }            
            $this->flashMessenger()->addSuccessMessage('Notes updated successfully');
            return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey, 'tab'=>6));
        }        
    }
    public function getTable()
    {
        if (!$this->table) {
            $sm = $this->getServiceLocator();
            $this->table= $sm->get('Applicants\Model\ApplicantsTable');
        }
        return $this->table;
    }
    public function ajaxTextEventAction()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();
        $data = $request->getPost();
        $result_array = array();
        if($request->isXmlHttpRequest()){
            $input_name_array = json_decode($data['input_name']);
            $pkey = $data['pkey'];
            if(count($input_name_array)>0)
            {
                foreach($input_name_array as $input_name)
                {
                    $columnValue = $this->getTable()->getColumnvalue($pkey,$input_name);
                    foreach($columnValue as $value){
                        if($value[$input_name]!='')
                        {    
                            $result_array[$input_name] = $value[$input_name];
                        }
                        else
                        {
                            $result_array[$input_name] = '';
                        }    
                    }
                }    
            }    
        }        
        $response->setContent(json_encode($result_array));        
        return $response;
    }   
    public function downloadAction()
    {
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        $doc = $this->getTable()->getDocumentsById($pkey);
        if(count($doc)>0)
        {            
            $doc_type = $doc->DocTypeLov_pkey;
            if($doc_type==12) $bussiness_type_id = 46;
            if($doc_type==1) $bussiness_type_id = 48;
            if($doc_type==3) $bussiness_type_id = 49;
            if($doc_type==13) $bussiness_type_id = 50;
            //Insert audittran
            $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
            //$doc->docFilePath = $_SERVER['DOCUMENT_ROOT'].'/'.$doc->docFilePath;
			$doc->docFilePath = '/'.$doc->docFilePath;
            if(file_exists($doc->docFilePath))
            {
                $audit_array = array();
                $audit_array['Newapplication_pkey'] = $doc->References_pkey;
                $audit_array['role'] = $this->getRole();
                $audit_array['user'] = $user_email;
                $audit_array['date'] = date('Y-m-d');     
                $audit_array['time'] = date('G:i:s');
                $audit_array['businessEventStatus'] = 'Success';
                $audit_array['message'] = 'Success';
                $audit_array['BusinessEventTypeEnum_pkey'] = $bussiness_type_id;                
                $this->getTable()->insertAuditApplicantHistory($audit_array);

                $fileName = $doc->docName;
                $fileContents = file_get_contents($doc->docFilePath);                    
                $response = $this->getResponse();                    
                $headers = $response->getHeaders();
                $headers->clearHeaders()
                        ->addHeaderLine('Content-Type', 'text/'.$doc->filextension)
                        ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                        ->addHeaderLine('Accept-Ranges', 'bytes')
                        ->addHeaderLine('Content-Length', strlen($fileContents));                    
                $response->setContent($fileContents);
                return $response;                    
            } 
            else
            {
                $audit_array = array();
                $audit_array['Newapplication_pkey'] = $doc->References_pkey;
                $audit_array['role'] = $this->getRole();
                $audit_array['user'] = $user_email;
                $audit_array['date'] = date('Y-m-d');     
                $audit_array['time'] = date('G:i:s');
                $audit_array['businessEventStatus'] = 'Failure';
                $audit_array['message'] = 'File not exist';
                $audit_array['BusinessEventTypeEnum_pkey'] = $bussiness_type_id;                
                $this->getTable()->insertAuditApplicantHistory($audit_array);
                
                $this->flashMessenger()->addErrorMessage('File not exist in the specified path');
                return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $doc->References_pkey));
            }
        }        
    }
    public function changeAppStatusAction()
    {
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $insertAppStatus = $this->getTable()->insertApplicationChangeHistory($post,$user_id);
            //Insert audittran
            $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
            $audit_array = array();
            $audit_array['Newapplication_pkey'] = $post->newApplication_pkey;
            $audit_array['role'] = $this->getRole();
            $audit_array['user'] = $user_email;
            $audit_array['date'] = date('Y-m-d');     
            $audit_array['time'] = date('G:i:s');
            $audit_array['businessEventStatus'] = 'Success';
            $audit_array['message'] = 'Success';
            $audit_array['BusinessEventTypeEnum_pkey'] = 54;                
            $this->getTable()->insertAuditApplicantHistory($audit_array);
            $this->flashMessenger()->addInfoMessage('The application status is changed');

            //updating actual status
            $this->getTable()->updateApplicationStatus($post->newApplication_pkey, $post->toStatus_pkey);
            //change applicantstatuslov to 2 (active) in partyinfohasstatus
            //check if toStatus_pkey is hired first
            if($post->toStatus_pkey ==  15){
                $this->getTable()->updateNewHireStatus($post->newApplication_pkey);
                return $this->redirect()->toRoute('faculty',array('action' => 'edit', 'pkey'=> $post->newApplication_pkey, 'tab'=>4));
            }

            return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $post->newApplication_pkey, 'tab'=>4));
        }        
    }
    public function saveFilterApplicantAction()
    {
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost();
            //Insert applicantfilter
            $applicant_filter_id = $this->getTable()->insertApplicantFilter($post->applicant_filter_name,$user_id);
            //Insert applicantfilterstatus
            if(isset($post->university) && !empty($post->university))
            {
                $this->getTable()->insertApplicantFilterTable('applicantfilteruniversity',$applicant_filter_id,'Partners_pkey',$post->university);                
            }
            //Insert applicantfilterstatus
            if(isset($post->state) && !empty($post->state))
            {                
                $this->getTable()->insertApplicantFilterTable('applicantfilterstate',$applicant_filter_id,'StateListLOV_pkey',$post->state);                
            }  
            //Insert applicantfilterinstructortype
            if(isset($post->instructor_type) && !empty($post->instructor_type))
            {                
                $this->getTable()->insertApplicantFilterTable('applicantfilterinstructortype',$applicant_filter_id,'InstructorTypeLOV_pkey',$post->instructor_type);                
            }
            //Insert applicantfilterdegree
            if(isset($post->highest_degree) && !empty($post->highest_degree))
            {                
                $this->getTable()->insertApplicantFilterTable('applicantfilterdegree',$applicant_filter_id,'EducationListEnum_pkey',$post->highest_degree);                
            }
            //Insert applicantfilterstatus
            if(isset($post->application_status) && !empty($post->application_status))
            {                
                $this->getTable()->insertApplicantFilterTable('applicantfilterstatus',$applicant_filter_id,'AppStatusLOV_pkey',$post->application_status);                
            }
            //Insert applicantfilterqualifiedtoteach
            if(isset($post->course) && !empty($post->course))
            {                
                $this->getTable()->insertApplicantFilterTable('applicantfilterqualifiedtoteach',$applicant_filter_id,'CourseDef_pkey',$post->course);                
            }
            //Insert applicantfilterdate
            if(isset($post->application_from_date) && $post->application_from_date!='' && isset($post->application_to_date) && $post->application_to_date!='')
            {                
                $this->getTable()->insertApplicantFilterDate($applicant_filter_id,$post->application_from_date,$post->application_to_date);                
            }
        }        
        $response = $this->getResponse();
        $response->setContent('Your filter is saved.You can always recall the saved filter by clicking the load filter button.');
        return $response;        
    }
    public function deleteFilterApplicantAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost();            
            if(isset($post->delete_availabe_filter) && count($post->delete_availabe_filter)>0)
            {
                $table_array = array('applicantfilteruniversity','applicantfilterstate','applicantfilterdegree','applicantfilterinstructortype','applicantfilterstatus','applicantfilterdate');
                $delete = $this->getTable()->deleteApplicantFilter($post->delete_availabe_filter,$table_array);
            }    
        } 
        $response = $this->getResponse();
        $response->setContent('Successfully Deleted');
        return $response;        
    }
    public function showQualifiedCoursesAction()
    {
        $request = $this->getRequest();
        $html = '';
        if ($request->isPost()) 
        {
            $post = $request->getPost();            
            $pkey = $post->pkey;                
            $coursesQualified = $this->getTable()->getcoursesQualifiedByApplicant($pkey);
            $class = '';
            if(count($coursesQualified)>0) $class = 'dialog-pagination-table';
            $html ='<table class="table '.$class.' " style="font-size:13px;">
                <thead>
                    <tr class="panel panel-default">
                        <th>Degree Code</th>
                        <th>Degree Name</th>
                        <th>Code</th>
                        <th>Title/Name</th>                        
                    </tr>
                </thead>
                <tbody>';
                    if(count($coursesQualified)>0) { 
                        foreach ($coursesQualified as $course): 
                            if($course->group_code!='') :
                                $course_group_code = $course->group_code;
                            else :
                                $course_group_code = '-';
                            endif;
                            if($course->group_name!='') :
                                $course_group_name = $course->group_name;
                            else :
                                $course_group_name = '-';
                            endif;
                $html .=' <tr class="panel panel-default">
                            <td>'.$course_group_code.'</td>
                            <td>'.$course_group_name.'</td>
                            <td>'.$course->code.'</td>                        
                            <td class="table-limit">'.$course->name.'</td>                            
                        </tr>';     
                        endforeach;
                        } else {
                    $html .='<tr class="panel panel-default"><td colspan="10">No records found.</td></tr>';
                     } 
                $html .='</tbody>
            </table>';
        } 
        $response = $this->getResponse();
        $response->setContent($html);
        return $response;
    }       
    public function saveQualifiedCoursesAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost();
            $this->getTable()->insertPartyInfoCourses($post);  
            //Insert audittran
            $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
            $audit_array = array();
            $audit_array['Newapplication_pkey'] = $post->Newapplication_pkey;
            $audit_array['role'] = $this->getRole();
            $audit_array['user'] = $user_email;
            $audit_array['date'] = date('Y-m-d');     
            $audit_array['time'] = date('G:i:s');
            $audit_array['businessEventStatus'] = 'Success';
            $audit_array['message'] = 'Success';
            $audit_array['BusinessEventTypeEnum_pkey'] = 51; 
            $this->getTable()->insertAuditApplicantHistory($audit_array);
        }
        $this->flashMessenger()->addInfoMessage('Courses Successfully Added');
        $response = $this->getResponse();        
        return $response; 
    }     
    public function removeQualifiedCoursesAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost(); 
            $party_course_ids = json_decode($post->party_course_ids);
            if(count($party_course_ids)>0)
            {
                $this->getTable()->deletePartyInfoCourses($party_course_ids);
            }   
            //Insert audittran
            $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
            $audit_array = array();
            $audit_array['Newapplication_pkey'] = $post->Newapplication_pkey;
            $audit_array['role'] = $this->getRole();
            $audit_array['user'] = $user_email;
            $audit_array['date'] = date('Y-m-d');     
            $audit_array['time'] = date('G:i:s');
            $audit_array['businessEventStatus'] = 'Success';
            $audit_array['message'] = 'Success';
            $audit_array['BusinessEventTypeEnum_pkey'] = 52;
            $this->getTable()->insertAuditApplicantHistory($audit_array);
        }  
        $this->flashMessenger()->addInfoMessage('Courses removed successfully.');
        $response = $this->getResponse();
        $response->setContent('3');
        return $response;
    }        

    public function generatePDFAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();  
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        if($pkey>0){
            $job = $this->getTable()->getJobDetailByPkey($pkey);            
        }        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new ApplicationForm($dbAdapter,$job->domainName); 
        $field_array = array();
        $data = array();
        foreach ($form->getElements() as $element) {
            $ele = $element->getName();
            $ele = str_replace('[]','',$ele);
            $label = $element->getLabel();
            if($label!=''){
                $field_array[$ele] = str_replace('*','',$label);
            }
        }
        
        if(count($field_array)>0){
            $data = $this->getTable()->getNewapplicationInfo($pkey,$field_array);
           /*foreach($data as &$row){
               if($row['key'] == 'S_ReasonLeaving1') {
                   //replacing the index value (ie. 1) with a human-meaningful value (ie. Discharged) in the HTML output
                    $row['value'] = $this->getTable()->getLookupValue($row['value'],'reason_leaving','name');
               } else if($row['key'] == 'S_MajorListEnum_pkey'){
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Adult Health Nursing) in the HTML output
                   $row['value'] = $this->getTable()->getLookupValue($row['value'],'majorlistenum','name');
               } else if($row['key'] == 'S_DegreeListEnum_pkey') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Bachelor of Arts) in the HTML output
                   $row['value'] = $this->getTable()->getLookupValue($row['value'],'degreelistenum','name');
               } else if($row['key'] == 'S_PaStateListLov_pkey') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. California) in the HTML output
                   $row['value'] = $this->getTable()->getLookupValue($row['value'],'statelistlov','name');
               } else if($row['key'] == 'S_AppState') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. California) in the HTML output
                   $row['value'] = $this->getTable()->getLookupValue($row['value'],'statelistlov','name');
               } else if($row['key'] == 'S_HereAboutUsEnum_pkey') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Other) in the HTML output
                   $row['value'] = $this->getTable()->getLookupValue($row['value'], 'hereaboutusenum', 'name');
               }
           }*/
        }
        
        $licensestypeapp = $this->getTable()->getLicensestypeapp($pkey);
        $fileName = 'FMSNewApplicationInfo.pdf';        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set font
        $pdf->SetFont('times', '', 12);
        // add a page
        $pdf->AddPage();
        //Content Starts here         
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');        
        $view = new ViewModel(array('job'=>$job,'result'=>$data, 'licensestypeapp'=>$licensestypeapp));
        $view->setTemplate("applicants/applicants/topdf");
        $pdfString = $viewRender->render($view);        
        $html = $pdfString;
        
        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);
        // reset pointer to the last page
        $pdf->lastPage();
        //Close and output PDF document
        //$file_base_path = '/home/serendio/hotchalkusers/fms/files/'.$user_email.'/'.time();
        $file_base_path = '/Home/hotchalkusers/fms/files/'.$user_email.'/'.time();
        mkdir($file_base_path, 0777, true);
//        shell_exec('mkdir -p bin '.$file_base_path);
//        shell_exec('chmod 777 -p bin '.$file_base_path);
        $documentsmetadata = array();
        $documentsmetadata['DocTypeLov_pkey'] = 13;
        $documentsmetadata['docName'] = $fileName;
        $documentsmetadata['docFilePath'] = $file_base_path.'/'.$fileName;
        $documentsmetadata['owner'] = $user_email;
        $documentsmetadata['References_pkey'] = $pkey; 	
        $documentsmetadata['transactionName'] = 'Job Application';
        $documentsmetadata['fileName'] = 'FMSNewApplicationInfo';
        $documentsmetadata['filextension'] = 'pdf';
        $documentsmetadata['submittedBy'] = $user_id;
        $documentsmetadata['date'] = date('Y-m-d');
        $documentsmetadata['refDate'] = date('Y-m-d');
        $documentsmetadata['submittedOn'] = date('Y-m-d');   
        $documentsmetadata['time'] = date('G:i:s');
        //Insert documentsmetadata
        $this->getTable()->insertDocumentmetadata($documentsmetadata);        
        
        //Home/hotchalkusers/fms/files/admin1@hotchalk.com/491251150/1413289035348/FMSNewApplicationInfo.pdf
        //$pdf->Output($file_base_path.'/'.$fileName, 'FD'); 
        $pdf->Output($file_base_path.'/'.$fileName, 'F');
        $this->flashMessenger()->addInfoMessage('PDF Generated Successfully');
        return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey, 'tab'=>2));
        
        /*
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        $applicant = $this->getTable()->getApplicantPDF($pkey);
        $wilkesemployerinfo = '';
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();       
        if(strtolower($applicant->domainName)=='wilkes')
        {   
            $wilkesemployerinfo = $this->getTable()->getWilkesemployerinfo($pkey);
        }       
        $licensestypeapp = '';
        if($applicant->domainName=='adler'){
            $licensestypeapp = $this->getTable()->getLicensestypeapp($pkey); 
        }
        $adler_degree_type = array();
        if($applicant->domainName=='adler'){
            $adler_degree_type = $this->getTable()->getAdlerDegreeType($pkey); 
        }
        $fileName = 'FMSNewApplicationInfo.pdf';        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set font
        $pdf->SetFont('times', '', 12);
        // add a page
        $pdf->AddPage();
        //Content Starts here         
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $view = new ViewModel(array('applicant'=>$applicant,'wilkesemployerinfo'=>$wilkesemployerinfo, 'licensestypeapp'=>$licensestypeapp, 'adler_degree_type'=>$adler_degree_type));
        $view->setTemplate("applicants/applicants/topdf");
        $pdfString = $viewRender->render($view);        
        $html = $pdfString;
        
        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);
        // reset pointer to the last page
        $pdf->lastPage();
        //Close and output PDF document
        //$file_base_path = '/home/saranya/hotchalkusers/fms/files/'.$user_email.'/'.time();
        $file_base_path = '/Home/hotchalkusers/fms/files/'.$user_email.'/'.time();
        shell_exec('mkdir -p bin '.$file_base_path);
        shell_exec('chmod 777 -p bin '.$file_base_path);
        $documentsmetadata = array();
        $documentsmetadata['DocTypeLov_pkey'] = 13;
        $documentsmetadata['docName'] = $fileName;
        $documentsmetadata['docFilePath'] = $file_base_path.'/'.$fileName;
        $documentsmetadata['owner'] = $user_email;
        $documentsmetadata['References_pkey'] = $applicant->pkey; 	
        $documentsmetadata['transactionName'] = 'Job Application';
        $documentsmetadata['fileName'] = 'FMSNewApplicationInfo';
        $documentsmetadata['filextension'] = 'pdf';
        $documentsmetadata['submittedBy'] = $user_id;
        $documentsmetadata['date'] = date('Y-m-d');
        $documentsmetadata['refDate'] = date('Y-m-d');
        $documentsmetadata['submittedOn'] = date('Y-m-d');   
        $documentsmetadata['time'] = date('G:i:s');
        //Insert documentsmetadata
        $this->getTable()->insertDocumentmetadata($documentsmetadata);        
        
        //Home/hotchalkusers/fms/files/admin1@hotchalk.com/491251150/1413289035348/FMSNewApplicationInfo.pdf
        $pdf->Output($file_base_path.'/'.$fileName, 'FD');     
        */
    } 
    public function ajaxTabApplicationViewAction()
    {
        $request = $this->getRequest();
        $html = '';
        if ($request->isPost()) 
        {
            $post = $request->getPost();            
            $pkey = $post->pkey;                
            $documents = $this->getTable()->getDocumentsByApplicant($pkey);
            $html .='<table class="table" style="font-size:14px;">
                <thead>
                    <tr class="panel panel-default">
                        <th>Generated Application(s)</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';             
                    if(count($documents)>0)
                    {
                        if(isset($documents['Generated PDF'])){
                            foreach($documents['Generated PDF'] as $doc){                                
                                $download_url = $this->url()->fromRoute('applicants', array('action'=>'download','pkey'=>$doc->pkey));
                                $html .= '<tr class="panel panel-default"><td><a href="'.$download_url.'">'.$doc->docName.'</a></td><td>'.date('m/d/Y',strtotime($doc->submittedOn)).' '.date('g:i A',strtotime($doc->time)).'</td></tr>';
                            }
                        }
                        else
                        {
                           $html .= '<tr class="panel panel-default"><td>No records found.</td></tr>'; 
                        } 
                    }
                    else
                    {
                       $html .= '<tr class="panel panel-default"><td>No records found.</td></tr>'; 
                    }    

                $html .='</tbody>                        
            </table>';             
        }
        $response = $this->getResponse();
        $response->setContent($html);
        return $response;
    }        
    public function uploadFileAction()
    {
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
        $file_base_path = '/Home/hotchalkusers/fms/files/'.$user_email.'/'.time().'/';                 
        //$file_base_path = '/home/svapas/hotchalkusers/fms/files/'.$user_email.'/'.time().'/';                 
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost(); 
            if($post->file_type=='cv') {
                $doc_type_id = 12;
                $bussiness_event_type = 40;
            }
            if($post->file_type=='license') {
                $doc_type_id = 1;  
                $bussiness_event_type = 41;
            }    
            if($post->file_type=='transcripts') {
                $doc_type_id = 3;
                $bussiness_event_type = 42;              
            }    
            $docName = $_FILES['files']['name'];
            $ext = pathinfo($docName, PATHINFO_EXTENSION);
            $fileName = pathinfo($docName, PATHINFO_FILENAME);
            //Insert into insertDocumentmetadata            
            $documentsmetadata = array();
            $documentsmetadata['DocTypeLov_pkey'] = $doc_type_id;
            $documentsmetadata['docName'] = $docName;
            $documentsmetadata['docFilePath'] = $file_base_path.$docName;
            $documentsmetadata['owner'] = $user_email;
            $documentsmetadata['References_pkey'] = $post->pkey; 	
            $documentsmetadata['transactionName'] = 'Job Application';
            $documentsmetadata['fileName'] = $fileName;
            $documentsmetadata['filextension'] = $ext;
            $documentsmetadata['submittedBy'] = $user_id;
            $documentsmetadata['date'] = date('Y-m-d');
            $documentsmetadata['refDate'] = date('Y-m-d');
            $documentsmetadata['submittedOn'] = date('Y-m-d');
            $doc_meta_pkey = $this->getTable()->insertDocumentmetadata($documentsmetadata); 
           
            //Insert into licensestypeapp
            if($post->file_type=='license') {
                $licensestypeapp = array();
                $licensestypeapp['NewApplication_pkey'] = $post->pkey; 
                $licensestypeapp['DocumentsMetaData_pkey'] = $doc_meta_pkey; 
                $licensestypeapp['filename'] = $docName;
                $doc_pkey = $this->getTable()->insertLicensestypeapp($licensestypeapp);
            }
            //Insert audittran            
            $audit_array = array();
            $audit_array['Newapplication_pkey'] = $post->pkey;
            $audit_array['role'] = $this->getRole();
            $audit_array['user'] = $user_email;
            $audit_array['date'] = date('Y-m-d');     
            $audit_array['time'] = date('G:i:s');
            $audit_array['businessEventStatus'] = 'Success';
            $audit_array['message'] = 'Success';
            $audit_array['BusinessEventTypeEnum_pkey'] = $bussiness_event_type;
            $this->getTable()->insertAuditApplicantHistory($audit_array);
        }       
        //Call Upload Class
        $upload_handler = new UploadHandler($options = null, $initialize = true, $error_messages = null,$file_base_path,$post->pkey,$doc_pkey);        
        $this->flashMessenger()->addInfoMessage('File Successfully Uploaded.');
        exit();
    }    
    public function removeDocsAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost(); 
            $docs_ids = json_decode($post->docs_ids);
            $docs_type = json_decode($post->docs_type);            
            if(count($docs_ids)>0)
            {
                $this->getTable()->deleteDocumentmetadata($docs_ids);
            } 
            if(count($docs_type)>0)
            {
                foreach($docs_type as $type)
                {
                    if($type=='cv') {                        
                        $bussiness_event_type = 43;
                    }
                    if($type=='license') {                        
                        $bussiness_event_type = 44;
                    }    
                    if($type=='transcripts') {                        
                        $bussiness_event_type = 45;              
                    }        
                }
                //Insert audittran  
                $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();
                $audit_array = array();
                $audit_array['Newapplication_pkey'] = $post->Newapplication_pkey;
                $audit_array['role'] = $this->getRole();
                $audit_array['user'] = $user_email;
                $audit_array['date'] = date('Y-m-d');     
                $audit_array['time'] = date('G:i:s');
                $audit_array['businessEventStatus'] = 'Success';
                $audit_array['message'] = 'Success';
                $audit_array['BusinessEventTypeEnum_pkey'] = $bussiness_event_type;
                $this->getTable()->insertAuditApplicantHistory($audit_array);
            }    
        }  
        $this->flashMessenger()->addInfoMessage('Files deleted successfully.');
        $response = $this->getResponse();
        $response->setContent('2');
        return $response;
    }        
    public function exportXLSAction()
    {
        $status = $this->params()->fromRoute('status', '');
        $applicant_session = new Container('applicant_session');        
        $result = $this->getTable()->getSearchResult($applicant_session->post,$status);
        //Applicants applied for multiple jobs from same school
        $content = '"University","First Name","Last Name","Email Address","Job Title","Instructor Type","Highest Degree","Qualified To Teach","Applicant Status","Notes","Application Submitted Date","Applied for Multiple Jobs"' . "\n\n";
        if(count($result)>0)
        {
            $alreadyApplied = $this->getTable()->getApplicantMultipleJobs($applicant_session->post,$status);
            foreach($result as $res)
            {
                $already_applied = 'No';
                if(isset($alreadyApplied[$res->partnersName])) {
                    if(in_array($res->email, $alreadyApplied[$res->partnersName])){
                        $already_applied = 'Yes';
                    }                                    
                }
                $coursesQualified = $this->getTable()->getcoursesQualifiedByApplicant($res->pkey);
                $course_detail = '--';
                $course_list = array();
                if(count($coursesQualified)>0) {
                    foreach($coursesQualified as $course){
                        $course_list[] = $course->code.' '.$course->name;
                    }
                }    
                if(count($course_list)>0) {
                    $course_detail = implode("|", $course_list);
                }    
                if($res->AppliedDate!='') $applied_date = date ('m/d/Y h:i A', strtotime($res->AppliedDate)); else $applied_date = '-';
                                
                $content .= '"'.$res->partnersName.'","'.$res->firstname.'","'.$res->lastname.'","'.$res->email.'","'.$res->jobname.'","'.$res->instructorType.'","'.$res->highest_degree.'","'.$course_detail.'","'.$res->app_status.'","'.$res->notes.'","'.$applied_date.'","'.$already_applied.'"' . "\n";
            }    
        }
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        /*
        $headers->addHeaderLine('Content-Type', 'application/vnd.ms-excel');
        $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"Applicants.xls\"");
        $headers->addHeaderLine('Content-Transfer-Encoding', 'binary');        
        $headers->addHeaderLine('Pragma', 'public');
        $headers->addHeaderLine('Content-Length', strlen($content));
        */
        $headers->addHeaderLine('Content-Type', 'text/csv');
        $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"Applicants.csv\"");
        $headers->addHeaderLine('Accept-Ranges', 'bytes');
        $headers->addHeaderLine('Content-Length', strlen($content));
        $response->setContent($content);
        return $response;
    }  
    public function exportCourseAction()
    {
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        if (!$pkey) {
            return $this->redirect()->toRoute('applicants', array(
                'action' => 'index'
            ));
        }
        $coursesQualified = $this->getTable()->getcoursesQualifiedByApplicant($pkey);
        $content = '"Course Group Name","Course Code","Course Name","Description"' . "\n\n";
        if(count($coursesQualified)>0)
        {
            foreach ($coursesQualified as $course):
                $content .= '"'.$course->group_name.'","'.$course->code.'","'.$course->name.'","'.$course->description.'"' . "\n";
            endforeach;
            
            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'text/csv');
            $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"Courses.csv\"");
            $headers->addHeaderLine('Accept-Ranges', 'bytes');
            $headers->addHeaderLine('Content-Length', strlen($content));
            $response->setContent($content);
            return $response;        
        }
    } 
    public function saveCandidateApprovedAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $post = $request->getPost();             
            $this->getTable()->saveCandidateApproved($post);
        }
        $this->flashMessenger()->addInfoMessage('Candidate Approved saved successfully.');
        $response = $this->getResponse();
        $response->setContent('3');
        return $response;
    }
    public function addLicenseAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $newapplication_pkey = $request->getPost()->get('NewApplication_pkey');
            $pkey = $request->getPost()->get('pkey');
            $documentType = $request->getPost()->get('documentType');
            $data = array();
            if(count($post)>0){
                foreach($post as $key => $value){
                    $data[$key] = $value;
                }
                if($documentType=='License'){
                    $data['otherissuingagency'] = null;
                    $data['othercertificationtype'] = null;
                    $data['IssuingAgencyLOV_pkey'] = null;
                    $data['CertificationTypeLOV_pkey'] = null;
                    $data['issuingagency'] = null;
                    $data['certificationtype'] = null;
                } else {
                    $data['stateOfIssue'] = null;
                    $data['otherlicensestype'] = null;
                    $data['LicensesTypeLOV_pkey'] = null;
                    $data['licensestype'] = null;
                }
            }
            unset($data['pkey']);        
            $this->getTable()->updateLicensestypeapp($data,$pkey);
            return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $newapplication_pkey, 'tab'=>1));            
        }    
        
    }
    
    public function checkDependencyAction(){ 
        $column_json = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $job = $this->getTable()->getJobDetailByPkey($post->pkey);        
            $subdomain = $job->domainName;        
            $university = $this->getTable()->getUniversityID($subdomain);            
            $columns = $this->getTable()->checkDependentColumns($post, $university);
            if(count($columns)>0){
                $column_json = json_encode($columns);
            }    
        }   
        $response = $this->getResponse();
        $response->setContent($column_json);
        return $response;
    }
    
    public function checkDependencySectionAction(){
        $column_json = '';
        $request = $this->getRequest();
        if ($request->isPost()) {            
            $post = $request->getPost();
            $job = $this->getTable()->getJobDetailByPkey($post->pkey);        
            $subdomain = $job->domainName;        
            $university = $this->getTable()->getUniversityID($subdomain);
            $columns = $this->getTable()->checkDependentSections($post, $university);
            if(count($columns)>0){
                $column_json = json_encode($columns);
            }
        }   
        $response = $this->getResponse();
        $response->setContent($column_json);
        return $response;
    }
    public function sendStatusChangeEmailAction(){
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $request = $this->getRequest();
        if ($request->isPost()) {            
            $post = $request->getPost();            
            $type = 'Status Change';
            $from_status = '';
            $to_status = '';
            $job = $this->getTable()->getJobDetailByPkey($post->newApplication_pkey);              
            $domainName = $job->domainName;                    
            $partyinfo = $this->getTable()->getPartyinfo($post->partyInfo_pkey);            
            $email_template = $this->getTable()->getEmailTemplate($type,$domainName,$job->pkey);            
            $appstatus = $this->getTable()->appStatusChangeHistory($post->newApplication_pkey);            
            if(count($appstatus)>0){
                $from_status = $appstatus[0]['from_status'];
                $to_status = $appstatus[0]['to_status'];
            }           
            //Email Alert    
            $to_name = $partyinfo->firstName.' '.$partyinfo->lastName;
            $to_email = $partyinfo->userId;
            $triggerMail = new EmailAlert(array());
            $mail_send = $triggerMail->sendStatusChangeEmail($to_email,$to_name,$from_status,$to_status,$email_template); 
            if($mail_send){
                $this->flashMessenger()->addInfoMessage('Mail send Successfully.');
            } else {
                $this->flashMessenger()->addErrorMessage('Mail sent error');
            }    
            return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $post->newApplication_pkey, 'tab'=>4));
        }
    }
    /*
     * Method to generate PDF for the STU and CU Colleges
     * 
     */
    public function PDFAction()
    {
        $pkey = (int) $this->params()->fromRoute('pkey', 0);
        $applicant = $this->getTable()->getApplicantPDF($pkey);        
        $user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $user_email = $this->zfcUserAuthentication()->getIdentity()->getEmail();       
        
        $fileName = 'FMSNewApplicationInfo.pdf';        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set font
        $pdf->SetFont('times', '', 12);
        // add a page
        $pdf->AddPage();
        //Content Starts here         
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $view = new ViewModel(array('applicant'=>$applicant));
        $view->setTemplate("applicants/applicants/pdf");
        $pdfString = $viewRender->render($view);        
        $html = $pdfString;
        
        // output the HTML content
        $pdf->writeHTML($html, true, 0, true, 0);
        // reset pointer to the last page
        $pdf->lastPage();
        //Close and output PDF document
        //$file_base_path = '/home/serendio/hotchalkusers/fms/files/'.$user_email.'/'.time();
        $file_base_path = '/Home/hotchalkusers/fms/files/'.$user_email.'/'.time();
        shell_exec('mkdir -p bin '.$file_base_path);
        shell_exec('chmod 777 -p bin '.$file_base_path);
        $documentsmetadata = array();
        $documentsmetadata['DocTypeLov_pkey'] = 13;
        $documentsmetadata['docName'] = $fileName;
        $documentsmetadata['docFilePath'] = $file_base_path.'/'.$fileName;
        $documentsmetadata['owner'] = $user_email;
        $documentsmetadata['References_pkey'] = $applicant->pkey; 	
        $documentsmetadata['transactionName'] = 'Job Application';
        $documentsmetadata['fileName'] = 'FMSNewApplicationInfo';
        $documentsmetadata['filextension'] = 'pdf';
        $documentsmetadata['submittedBy'] = $user_id;
        $documentsmetadata['date'] = date('Y-m-d');
        $documentsmetadata['refDate'] = date('Y-m-d');
        $documentsmetadata['submittedOn'] = date('Y-m-d');   
        $documentsmetadata['time'] = date('G:i:s');
        //Insert documentsmetadata
        $this->getTable()->insertDocumentmetadata($documentsmetadata);        
        
        //Home/hotchalkusers/fms/files/admin1@hotchalk.com/491251150/1413289035348/FMSNewApplicationInfo.pdf
        //$pdf->Output($file_base_path.'/'.$fileName, 'FD');        
        $pdf->Output($file_base_path.'/'.$fileName, 'F');
        $this->flashMessenger()->addInfoMessage('PDF Generated Successfully');
        return $this->redirect()->toRoute('applicants',array('action' => 'edit', 'pkey'=> $pkey, 'tab'=>2));
    }
}

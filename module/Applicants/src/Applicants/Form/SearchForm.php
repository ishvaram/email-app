<?php

namespace Applicants\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
class SearchForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter,$applicant_session_post)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);
        parent::__construct('applicants');
                        
        $univerity = isset($applicant_session_post['university']) ? $applicant_session_post['university'] : '';
        $instructor_type = isset($applicant_session_post['instructor_type']) ? $applicant_session_post['instructor_type'] : '';
        $highest_degree = isset($applicant_session_post['highest_degree']) ? $applicant_session_post['highest_degree'] : '';        
        $state = isset($applicant_session_post['state']) ? $applicant_session_post['state'] : '';
        $application_status = isset($applicant_session_post['application_status']) ? $applicant_session_post['application_status'] : '';
        $application_from_date = (!empty($applicant_session_post['application_from_date'])) ? date('m/d/Y',  strtotime($applicant_session_post['application_from_date'])) : '';
        $application_to_date = (!empty($applicant_session_post['application_to_date'])) ? date('m/d/Y',  strtotime($applicant_session_post['application_to_date'])) : '';
        $course = isset($applicant_session_post['course']) ? $applicant_session_post['course'] : '';    
        $job = isset($applicant_session_post['job']) ? $applicant_session_post['job'] : '';
        
        $enabledFilters = $this->getEnabledFilters();
        /*
         * Variables needed:
         * $application_to_date
         * $state
         * $instructor_type
         * $highest_degree
         * $application_status
         * $application_from_date
         * $course
         */
        $requestValues = array(
            'application_to_date' => $application_to_date,
            'application_from_date' => $application_from_date,
            'state' => $state,
            'instructor_type' => $instructor_type,
            'highest_degree' => $highest_degree,
            'application_status' => $application_status,
            'course' => $course,
            'job' => $job,
        );
        $this->loadFilters($enabledFilters,$requestValues);
        
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
                'name' => 'availabe_filter',
                'options' => array(
                'label' => 'Available Filters',
                'required' => false,
                'value_options' => $this->getAvailableFilters('list'),
            ),
            'attributes' => array(                
                'class' => 'form-control',
                'value' => isset($_REQUEST['availabe_filter']) ? $_REQUEST['availabe_filter'] : '',
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
                'name' => 'delete_availabe_filter',
                'options' => array(
                'label' => 'Available Filters',
                'required' => false,
                'value_options' => $this->getAvailableFilters(),
            ),
            'attributes' => array(         
                'multiple' => 'multiple',
                'class' => 'search-option',                
            )
        ));
        
        $this->add(array(
            'name' => 'filter_type',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => '',
            ),
        ));
        $this->add(array(
            'name' => 'load_filter',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Load Filter',
                'id' => 'submitbutton',
                'class' => 'btn btn-small btn-primary',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
                'name' => 'university',
                'options' => array(
                'label' => 'University',
                'required' => false,
                'value_options' =>$this->getUniversityList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $univerity
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
                'name' => 'job',
                'options' => array(
                'label' => 'Job',
                'required' => false,
                'value_options' =>$this->getJobList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $job
            )
        ));
        
        $this->add(array(
            'name' => 'search-submit',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Apply Filter',
                'id' => 'submitbutton',
                'class' => 'btn btn-small btn-primary',
            ),
        ));
        $this->add(array(
            'name' => 'save_filter',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Save Filter',                    
                'class' => 'btn btn-small btn-primary',
                'onclick' => "Popup.show('saveFilterCnt');return false;",
            ),
        ));
        $this->add(array(
            'name' => 'delete_filter',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Delete Filter',                
                'class' => 'btn btn-small btn-primary',
                //'onclick' => "Popup.show('deleteFilterCnt');return false;",
            ),
        ));
        $this->add(array(
            'name' => 'clear_filter',
            'attributes' => array(
                'type'  => 'button',
                'value' => 'Clear',                    
                'class' => 'btn btn-small btn-primary',                
            ),
        ));
    }
    public function getUniversityList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM partners ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    } 
    public function getJobList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM joblisting ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }
    public function getInstructorList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM instructortypelov ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }
    public function getHighestDegreeList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM educationlistenum ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }        
    public function getStateList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM statelistlov ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }        
    public function getApplicationStatusList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM appstatuslov ORDER BY sortOrder ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData; 
    }        
    public function getCoursesList()
    {
        $dbAdapter = $this->getDbAdapter();                        
        //$sql = 'SELECT cd.pkey As pkey,cd.code AS code,d.code AS group_code FROM coursesdef AS cd JOIN degreeprogramlevellist AS d ON d.pkey = cd.DegreeProgramLevelList_pkey ORDER BY cd.code ASC';    
        $sql = 'SELECT cd.pkey As pkey,cd.code AS code,cd1.code AS group_code FROM coursedef AS cd JOIN coursedef AS cd1 ON cd1.pkey = cd.parentReference_pkey ORDER BY code ASC';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['group_code'].' / '.$res['code'];
        }
        return $selectData; 
    } 
    public function getAvailableFilters($type='')
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM applicantfilter ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();  
        if($type=='list')
        {
            $selectData[] = '';
        }
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }        

    public function setDbAdapter(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }	
    public function getEnabledFilters()
    {
        //get enabled filters
        $dbAdapter = $this->getDbAdapter();
        $sql = "select so.pkey,ote.name,ote.label,ote.table,ote.column,so.sort_order
                from siteoptions as so
                join optiontypeenum as ote
                on (ote.pkey = so.OptionTypeEnum_pkey)
                where so.enable = 1
                and ote.OptionCategoryTypeEnum_pkey = 1
                order by so.sort_order asc";
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $filtersData = array();
        foreach ($result as $res) {
            $filtersData[] = array(
                'id'=>$res['pkey'],
                'name' => $res['name'],
                'alias' => $res['label'],
                'table' => $res['table'],
                'column' => $res['column'],
            );
        }
        return $filtersData;
    }
    public function loadFilters($enabledFilters,$requestValues){
        //count filters
        $filterCount = count($enabledFilters) + 1;
        $quotient = intval($filterCount / 3);
        $remainder = $filterCount % 3;
        $rowCountView = $quotient;
        if($remainder > 0){
            $rowCountView++;
        }

        //moved the in individual functions so we can modularize the filters
        $this->_rowCountView = $rowCountView;
        $formFilters = array('university');
        $formFilters[] = 'job';
        foreach($enabledFilters as $filter){
            $functionName = 'add_'.$filter['name'];
            if(method_exists($this,$functionName)){
                $this->$functionName($requestValues,$formFilters);
            }else{
                //function does not exist
            }
//            exit();
        }
        $this->_formFilters = $formFilters;
    }

    private function add_Applicant_StateOfResidency($requestValues,&$formFilters){
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'state',
            'options' => array(
                'label' => 'State of Residency',
                'required' => false,
                'value_options' =>$this->getStateList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $requestValues['state']
            )
        ));
        $formFilters[] = 'state';
    }

    private function add_Applicant_InstructorType($requestValues,&$formFilters){
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'instructor_type',
            'options' => array(
                'label' => 'Instructor Types',
                'required' => false,
                'value_options' =>$this->getInstructorList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $requestValues['instructor_type']
            )
        ));
        $formFilters[] = 'instructor_type';
    }

    private function add_Applicant_ApplicationStatus($requestValues,&$formFilters){
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'application_status',
            'options' => array(
                'label' => 'Application Status',
                'required' => false,
                'value_options' =>$this->getApplicationStatusList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $requestValues['application_status']
            )
        ));
        $formFilters[] = 'application_status';
    }

    private function add_Applicant_ApplicationSubmittedFrom($requestValues,&$formFilters){
        $this->add(array(
            'name' => 'application_from_date',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'search-form-control',
                'value' => $requestValues['application_from_date'],
                'placeholder' => 'mm/dd/yyyy',
            ),
            'options' => array(
                'label' => 'Application Submitted From Date',
            ),
        ));
        $formFilters[] = 'application_from_date';
    }

    private function add_Applicant_ApplicationSubmittedTo($requestValues,&$formFilters){
        $this->add(array(
            'name' => 'application_to_date',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'search-form-control',
                'value' => $requestValues['application_to_date'],
                'placeholder' => 'mm/dd/yyyy',
            ),
            'options' => array(
                'label' => 'Application Submitted To Date',
            ),
        ));
        $formFilters[] = 'application_to_date';
    }

    private function add_Applicant_ApplicationHighestDegree($requestValues,&$formFilters){
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'highest_degree',
            'options' => array(
                'label' => 'Highest Degree',
                'required' => false,
                'value_options' =>$this->getHighestDegreeList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $requestValues['highest_degree']
            )
        ));
        $formFilters[] = 'highest_degree';
    }
    private function add_Applicant_QualifiedToTeach($requestValues,&$formFilters){        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'course',
            'options' => array(
                'label' => 'Qualified To Teach',
                'required' => false,
                'value_options' =>$this->getCoursesList(),
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'class' => 'search-option',
                'value' => $requestValues['course']
            )
        ));
        $formFilters[] = 'course';
    }
}
?>

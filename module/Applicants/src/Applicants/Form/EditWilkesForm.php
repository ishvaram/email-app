<?php

/* 
 * Applicants Wilkes - Edit Form 
 */

namespace Applicants\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EditWilkesForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter,$applicant)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);
	parent::__construct('applicants');		
        $this->setAttribute('method', 'post');		
      	$this->add(array(
            'name' => 'pkey',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => '',
            ),
        ));	
        $this->add(array(
            'name' => 'applicationName',
            'options' => array(
                'label' => 'Applicant Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
      	$this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
            ),
            'attributes' => array(
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'partnersName',
            'options' => array(
                'label' => 'University',
            ),
            'attributes' => array(
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'school_name',
            'options' => array(
                'label' => 'School',
            ),
            'attributes' => array(
                'type' => 'text',
                'readonly' => true,
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'firstname',
            'options' => array(
                'label' => 'First Name <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'lastname',
            'options' => array(
                'label' => 'Last Name <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));        
        $this->add(array(
            'name' => 'instructor_type_name',
            'options' => array(
                'label' => 'Instructor Type <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'readonly' => true,
                'class' => 'form-control',
             ),
        ));        
         
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'HereAboutUsEnum_pkey',
            'options' => array(
                'label' => 'How did you hear about this opening? <span class="required">*</span>',
                'required' => true,
                'value_options' => $this->getHereAboutUsList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'job_name',
            'options' => array(
                'label' => 'Job Name',
            ),
            'attributes' => array(
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:500px !important',
             ),
        ));
        $this->add(array(
            'name' => 'presentSalary',
            'options' => array(
                'label' => 'Salary',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        
        $this->add(array(
            'type' => 'text',
            'name' => 'AppliedDate',
            'options' => array(
                'label' => 'Applied Date',
            ),
            'attributes' => array(               
                'readonly' => true,
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'availableDate',
            'options' => array(
                'label' => 'Date Available <br/>for Work <span class="required">*</span>',
            ),
            'attributes' => array(                               
                'required' => true,
                'class' => 'form-control date_pick',                
             ),
        )); 
        $this->add(array(
            'type' => 'text',
            'name' => 'middleName',
            'options' => array(
                'label' => 'Middle Name',
            ),
            'attributes' => array(                               
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'telephoneNo',
            'options' => array(
                'label' => 'Telephone No <span class="required">*</span>',
            ),
            'attributes' => array(                               
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'socialSecurityNo',
            'options' => array(
                'label' => 'Social Security No <span class="required">*</span>',
            ),            
            'attributes' => array(     
                'readonly' => true,
                'required' => true,
                'class' => 'form-control',                
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'castreet',
            'options' => array(
                'label' => 'Street <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'cabox',
            'options' => array(
                'label' => 'Box',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'cacity',
            'options' => array(
                'label' => 'City <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'CaStateListLov_pkey',
            'options' => array(
                'label' => 'State <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getStateList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'cazip',
            'options' => array(
                'label' => 'Zip <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'pastreet',
            'options' => array(
                'label' => 'Street <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'pabox',
            'options' => array(
                'label' => 'Box',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'pacity',
            'options' => array(
                'label' => 'City <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'PaStateListLov_pkey',
            'options' => array(
                'label' => 'State <span class="required">*</span>',
                'required' => true,
                'value_options' => $this->getStateList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'pazip',
            'options' => array(
                'label' => 'Zip <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'otherNames',
            'options' => array(
                'label' => 'Other names you have used',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'ifagedovereighteen',
            'options' => array(
                'label' => 'Are you 18 year of age or older? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            )            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isUSAuthorizedWorker',
            'options' => array(
                'label' => 'Are you legally entitled to work in the United States? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            )            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isCityofPhiladelphia',
            'options' => array(
                'label' => 'Are you a resident of City of Philadelphia? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(
                'onclick'  => 'openPanel(this,"isFelonyConvicted",0)',
            ),
        ));
        $required = false;
        if($applicant->isCityofPhiladelphia==0) $required = true;
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isFelonyConvicted',
            'options' => array(
                'label' => 'Have you ever been convicted of or pleaded guilty or Nolo Contendre (no contest or an Alford plea) to any felony? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"isFelonyYes",1)',
                'required' => $required,
            ),
        ));
        if($applicant->isCityofPhiladelphia==0 && $applicant->isFelonyConvicted==1) $required = true;
        $this->add(array(
            'type' => 'textarea',
            'name' => 'felonyIfYes',
            'options' => array(
                'label' => 'The fact you have been convicted of a crime will not automatically disqualify you from further consideration <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'hignSchool',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'highSchoolAddress',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'highSchoolTelephoneNo',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'ishighSchoolGraduate',
            'options' => array(
                'label' => 'Did you graduate? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"panelnoHighSchool",0)',
            ),
        ));
        $required = false;
        if($applicant->ishighSchoolGraduate==0) $required = true;
        $this->add(array(
            'type' => 'text',
            'name' => 'ifnoHighSchoolGraduate',
            'options' => array(
                'label' => 'If no, last grade completed <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'gedObtained',
            'options' => array(
                'label' => 'G.E.D. Obtained? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ), 
            'attributes' => array(                     
                'required' => $required,
            ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'college1',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'collegeAddress1',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'collegeTelephoneNo1',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));         	 	
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'iscollegeGraduate',
            'options' => array(
                'label' => 'Did you graduate? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"panelnoCollege",0)',
            ),
        ));
        $required = false;
        if($applicant->iscollegeGraduate==0) $required = true;
        $this->add(array(
            'type' => 'text',
            'name' => 'ifnocollegeGraduate',
            'options' => array(
                'label' => 'If no, number of hours completed <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'gradePointAvg',
            'options' => array(
                'label' => 'Grade Point Average <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'DegreeListEnum_pkey',
            'options' => array(
                'label' => 'Degree <span class="required">*</span>',
                'required' => true,
                'value_options' => $this->getDegreeList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'degreeOther',
            'options' => array(
                'label' => 'If other, please specify. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'MajorListEnum_pkey',
            'options' => array(
                'label' => 'Major <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getMajorList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'majorOther',
            'options' => array(
                'label' => 'If other, please specify. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'minor',
            'options' => array(
                'label' => 'Minor/Concentration (if none, enter N/A) <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'otherEducation',
            'options' => array(
                'label' => 'Other Education <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'certification',
            'options' => array(
                'label' => 'Certifications <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'awards',
            'options' => array(
                'label' => 'Awards, Honors, Leadership Roles <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'foreignLanguage',
            'options' => array(
                'label' => 'Foreign Languages <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'militaryApplicability',
            'options' => array(
                'label' => 'Are you currently active in the Military? <span class="required">*</span>',
                'value_options' => array(
                    'Active'=>'Active ',
                    'Retired' =>' Retired',
                    'NotApplicable' => ' Not Applicable',
                ),
            ),
            'attributes' => array(
                'onclick'  => 'openmilitaryPanel(this)',
            ),
        ));
        $required = false;
        $required_military = false;
        if($applicant->militaryApplicability=='Retired' || $applicant->militaryApplicability=='Active') $required = true;
        if($applicant->militaryApplicability=='Retired') $required_military = true;
        $this->add(array(
            'type' => 'text',
            'name' => 'militaryservicefrom',
            'options' => array(
                'label' => 'From <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control date_pick',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'militaryserviceto',
            'options' => array(
                'label' => 'To <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control date_pick',
             ),
        )); 	
        $this->add(array(
            'type' => 'text',
            'name' => 'militaryBranch',
            'options' => array(
                'label' => 'Branch <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
             ),
        ));  
        $this->add(array(
            'type' => 'text',
            'name' => 'militaryrank',
            'options' => array(
                'label' => 'Rank at Discharge <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required_military,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'textarea',
            'name' => 'militaryExp',
            'options' => array(
                'label' => 'Military experience that may be applicable  <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'textarea',
            'name' => 'skillsAndExp',
            'options' => array(
                'label' => 'List all specialized skills with which you have experience and training. (Example: PC/MAC applications) <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isPreviouslyEmployed',
            'options' => array(
                'label' => 'Were you previously employed by us? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"yesFromEmp",1)',
            ),
        )); 
        $required = false;
        if($applicant->isPreviouslyEmployed==1) $required = true;
        $this->add(array(
            'type' => 'text',
            'name' => 'ifyesFromEmp',
            'options' => array(
                'label' => 'If yes, when? <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control date_pick',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'ifyesToEmp',
            'options' => array(
                'label' => 'To <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control date_pick',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'presentSalaryEmp',
            'options' => array(
                'label' => 'Present Salary <span class="required">*</span><label style="font-size: 14px;font-style: italic;color: #999999;">  (hr/week/year)</label>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'salaryExpectedEmp',
            'options' => array(
                'label' => 'Salary Expected <span class="required">*</span><label style="font-size: 14px;font-style: italic;color: #999999;">  (hr/week/year)</label>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'hoursAvailableEmp',
            'options' => array(
                'label' => 'Number of hours available per week <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'regularFullTime',
            'options' => array(
                'label' => 'Regular full time',                
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'regularHalfTime',
            'options' => array(
                'label' => 'Regular part time',                
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'temporary',
            'options' => array(
                'label' => 'Temporary',                
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'seasonal',
            'options' => array(
                'label' => 'Seasonal',                
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'days',
            'options' => array(
                'label' => 'Days <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        )); 
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'night',
            'options' => array(
                'label' => 'Nights <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'weekends',
            'options' => array(
                'label' => 'Weekends <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'holidays',
            'options' => array(
                'label' => 'Holidays <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'textarea',
            'name' => 'relativeNameList',
            'options' => array(
                'label' => 'List the names of relatives currently in our employ. If none, enter N/A <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'emergencyContactName',
            'options' => array(
                'label' => 'In case of emergency, notify <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'emergencyContactNo',
            'options' => array(
                'label' => 'Telephone Number <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'canYouTravel',
            'options' => array(
                'label' => 'Can you travel if a job requires it? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'textarea',
            'name' => 'specialTrainingandInterest',
            'options' => array(
                'label' => 'Please list any information which you feel may be helpful in considering your application. For example: significant work accomplishments, special training, specific interests, etc. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'textarea',
            'name' => 'listAffiliations',
            'options' => array(
                'label' => 'Please list affiliations with professional, civic organizations which you consider relevant to your ability to perform the job for which you are applying. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isContractualObligations',
            'options' => array(
                'label' => 'Do you have any contractual obligations relating to a prior employer or client, such as a Confidentiality and/or Non-Compete Agreement? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ), 
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"contractualObligations",1)',
            ),
        ));
        $required = false;
        if($applicant->isContractualObligations==1) $required = true;
        $this->add(array(
            'type' => 'textarea',
            'name' => 'ifyesContractualObligations',
            'options' => array(
                'label' => 'If so, please list the agreements, dates and employers/clients involved. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' => $required,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'referenceName',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceAddress',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referencePhoneNumber',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceOccupation',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceName2',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceAddress2',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referencePhoneNumber2',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceOccupation2',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceName3',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceAddress3',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referencePhoneNumber3',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'referenceOccupation3',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'signature',
            'options' => array(
                'label' => 'Signature (Type in Name) <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                                  
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'mayContactEmp',
            'options' => array(
                'label' => 'May we contact your most recent employer? <span class="required">*</span>',
                'value_options' => array(
                    '1'=>' Yes ',
                    '0' =>' No ',                    
                ),
            ),            
        ));        
 	$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',
                'id' => 'submitbutton',
		'class' => 'btn btn-large btn-primary',
            ),
        ));	
        //Additional Information ...
        $this->add(array(
            'name' => 'additional_university',
            'options' => array(
                'label' => 'University where Highest Degree was Attained',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_profit_nonprofit',
            'options' => array(
                'label' => 'For-Profit or Non-Profit',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_highest_degree',
            'options' => array(
                'label' => 'Highest Degree Attained and Concentration',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
	$this->add(array(
            'name' => 'additional_payrate',
            'options' => array(
                'label' => 'Pay Rate',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_university_email',
            'options' => array(
                'label' => 'University Email Address',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_education_exp',
            'options' => array(
                'label' => 'Years of Experience in Education Field',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_online_exp',
            'options' => array(
                'label' => 'Years of Online Experience',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_start_date',
            'options' => array(
                'label' => 'Official Start Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',                
            ),
        ));
        $this->add(array(
            'name' => 'additional_job_type',
            'options' => array(
                'label' => 'Part Time or Full Time',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'submit-additional',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',                
		'class' => 'btn btn-large btn-primary',                
            ),
        ));
        $this->add(array(
            'name' => 'notes',
            'options' => array(
                'label' => 'Notes',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'submit-notes',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',                
		'class' => 'btn btn-large btn-primary',                
            ),
        ));
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
    public function getHereAboutUsList() 
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM hereaboutusenum';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();   
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }
    public function getStateList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM statelistlov';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();    
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;                
    }
    public function getDegreeList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM degreelistenum ORDER BY name';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();  
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }
    public function getMajorList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM majorlistenum ORDER BY name';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }
    
         
}
?>



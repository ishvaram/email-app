<?php

/* 
 * Applicants - Edit Form 
 */

namespace Applicants\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EditForm extends Form
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
            'name' => 'homePhone',
            'options' => array(
                'label' => 'Home Phone <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'workPhone',
            'options' => array(
                'label' => 'Work Phone',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'cellPhone',
            'options' => array(
                'label' => 'Cell Phone <span class="required">*</span>',
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
            'name' => 'job_name',
            'options' => array(
                'label' => 'Job Name',
            ),
            'attributes' => array(
                'type' => 'text',
                'readonly' => true,
                'class' => 'form-control',                
             ),
        ));
       
        $this->add(array(
            'name' => 'address',
            'options' => array(
                'label' => 'Address 1 <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'address2',
            'options' => array(
                'label' => 'Address 2',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'name' => 'city',
            'options' => array(
                'label' => 'City <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'CountryLOV_pkey',
            'options' => array(
                'label' => 'Country <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getCountryList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'StateListLov_pkey',
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
            'name' => 'zip',
            'options' => array(
                'label' => 'Zip <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isUSAuthorizedWorker',
            'options' => array(
                'label' => 'Are you legally authorized to work in the U.S.? <span class="required">*</span> ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            )            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isFelonyConvicted',
            'options' => array(
                'label' => 'Have you ever been convicted of a felony? <span class="required">*</span> ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),
            'attributes' => array(                
                'onclick'  => 'openPanel(this,"isFelonyYes",1)',
            ),
        ));  
        $required = false;
        if($applicant->isFelonyConvicted==1) $required = true;
        $this->add(array(
            'type' => 'textarea',
            'name' => 'felonyIfYes',
            'options' => array(
                'label' => 'If yes, please explain. Conviction will not necessarily disqualify an applicant from employment. <span class="required">*</span>',
            ),
            'attributes' => array(                     
                'required' =>  $required,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'EducationListEnum_pkey',
            'options' => array(
                'label' => 'Highest degree attained <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getHighestDegreeList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));       
        $this->add(array(
            'name' => 'totalExp',
            'options' => array(
                'label' => 'Total years relevant experience in your profession <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
             ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'onlinePostsecondaryTeachingExp',
            'options' => array(
                'label' => 'How many years of online postsecondary teaching experience do you have? <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getOnlinePostSecondaryExpList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        
        $this->add(array(
            'name' => 'signature',
            'options' => array(
                'label' => 'Signature',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isCurrentlyEmployed',            
            'options' => array(
                'label' => 'Are you currently employed? ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),
        ));
        $this->add(array(
            'name' => 'currentEmployer',
            'options' => array(
                'label' => 'Current employer',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isEverEmpInThisUniv',            
            'options' => array(
                'label' => '',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),
        ));
        $this->add(array(
            'name' => 'univExpDetails',
            'options' => array(
                'label' => 'If so, please list the timeframe and location.',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'isAnyRelativesEmployedHere',            
            'options' => array(
                'label' => '',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),
        ));
        $this->add(array(
            'name' => 'conRefName',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'conRefRelationship',
            'options' => array(
                'label' => 'Relationship',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'conRefLocation',
            'options' => array(
                'label' => 'Location',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'religion_name',
            'options' => array(
                'label' => 'Religious affiliation',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'churchMemOrdenominationalAffiliation',
            'options' => array(
                'label' => 'If other, please specify the religion.',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'churchMemOrdenominationalCongregation',
            'options' => array(
                'label' => 'Religious congregation',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'teachingPhilosophy',
            'options' => array(
                'label' => 'Describe your teaching philosophy in no more than one page.',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:100%',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsWinter',
            'options' => array(
                'label' => 'Winter',                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsFall',
            'options' => array(
                'label' => 'Fall',                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsSummer',
            'options' => array(
                'label' => 'Summer',                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'IsSpring',
            'options' => array(
                'label' => 'Spring',                
            ),
            'attributes' => array(
                'disabled' => 'disabled',
            ),            
        ));
        $this->add(array(
            'name' => 'listOfAreaQualifiedToTeach',
            'options' => array(
                'label' => 'Please list those areas in which you are qualified to teach',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:100%',
            ),
        ));
        $this->add(array(
            'name' => 'currentTeachingInfo',
            'options' => array(
                'label' => 'Currently Teaching and/or Administrative Responsibilities',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:100%',
            ),
        ));
        $this->add(array(
            'name' => 'educationalBackground',
            'options' => array(
                'label' => 'Educational background',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:100%',
            ),
        ));
        $this->add(array(
            'name' => 'honorsRecieved',
            'options' => array(
                'label' => 'Honors Received',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'readonly' => true,
                'class' => 'form-control',
                'style' => 'width:100%',
            ),
        ));
        $this->add(array(
            'name' => 'listDegreeSpeciality',
            'options' => array(
                'label' => 'List Degree Speciality',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
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
                'readonly' => true,
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
                'readonly' => true,
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
                'readonly' => true,
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
                'readonly' => true,
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
                'readonly' => true,
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
                'readonly' => true,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'referenceEmail',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'referenceEmail2',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'referenceEmail3',
            'options' => array(
                'label' => '',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',                
            ),
        ));
        $this->add(array(
            'name' => 'hereAboutUs',
            'options' => array(
                'label' => 'How did you learn about this position?',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'readonly' => true,
                'class' => 'form-control',                
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
    public function getCountryList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM countrylov';        
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
    public function getHighestDegreeList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM educationlistenum';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();   
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }   
    public function getMethodologyList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM methodology';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();   
        $selectData[] = '';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }       
    public function getOnlinePostSecondaryExpList()
    {
        $selectData = array();
        $selectData[] = '';
        $selectData['Less than 1 year'] = 'Less than 1 year';
        $selectData['1 year'] = '1 year';
        $selectData['2 years'] = '2 years';
        $selectData['3 years'] = '3 years';
        $selectData['4 years'] = '4 years';
        $selectData['5 years and more'] = '5 years and more';
        return $selectData;
    }        
}
?>



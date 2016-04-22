<?php

/* 
 * Applicants Adler - Edit Form 
 */

namespace Applicants\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EditAdlerForm extends Form
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
        /*$this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_PrefSched',
            'options' => array(
                'label' => 'Preferred teaching schedule',
                'required' => false,
                'value_options' => $this->getPreferredTeachingList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_PrefTime',
            'options' => array(
                'label' => 'Preferred Time',
                'required' => false,
                'value_options' => $this->getPreferredTimeList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        */
        $this->add(array(
            'name' => 'S_AppLastName',
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
            'name' => 'S_AppFirstName',
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
            'name' => 'S_AppMiddleName',
            'options' => array(
                'label' => 'Middle Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_AppAddress',
            'options' => array(
                'label' => 'Address <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_AppCity',
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
            'name' => 'S_AppState',
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
            'name' => 'S_AppZipCode',
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
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_AppCountry',
            'options' => array(
                'label' => 'Country <span class="required">*</span>',
                'required' => true,
                'value_options' => $this->getCountryList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        
        
        $this->add(array(
            'name' => 'S_AppEmail',
            'options' => array(
                'label' => 'Email <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_AppPhoneNum1',
            'options' => array(
                'label' => 'Phone <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_AppPhoneType1',
            'options' => array(
                'label' => 'Type',
                'required' => true,
                'value_options' => $this->getPhoneTypeList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_AppPhoneNum2',
            'options' => array(
                'label' => 'Phone',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_AppPhoneType2',
            'options' => array(
                'label' => 'Type',
                'required' => true,
                'value_options' => $this->getPhoneTypeList(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_EmerName1',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmerPhoneNum1',
            'options' => array(
                'label' => 'Phone number',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmerRelation1',
            'options' => array(
                'label' => 'Relationship',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));        
        $this->add(array(
            'name' => 'S_EmerName2',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmerPhoneNum2',
            'options' => array(
                'label' => 'Phone number',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmerRelation2',
            'options' => array(
                'label' => 'Relationship',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_AuthorizedUS',
            'options' => array(
                'label' => 'Are you legally authorized to work in the United States? <span class="required">*</span> ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',
                ),
            ),  
            'attributes' => array(                     
                'required' =>  true,                
            ),
        ));        
        $this->add(array(
            'name' => 'S_RefName1',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefEmail1',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefPhone1',
            'options' => array(
                'label' => 'Phone',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefName2',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefEmail2',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefPhone2',
            'options' => array(
                'label' => 'Phone',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefName3',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefEmail3',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_RefPhone3',
            'options' => array(
                'label' => 'Phone',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));        
        $this->add(array(
            'name' => 'S_Position1',
            'options' => array(
                'label' => '1. Position Title/Rank',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_StartDate1',
            'options' => array(
                'label' => 'Start Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_EndDate1',
            'options' => array(
                'label' => 'End Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_Empl1',
            'options' => array(
                'label' => 'Employer',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ImmedSupervisor1',
            'options' => array(
                'label' => 'Immediate Supervisor',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplAdd1',
            'options' => array(
                'label' => 'Employer Address',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplCity1',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplState1',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplZip1',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Responsiblities1',
            'options' => array(
                'label' => 'Responsibilities',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_CoursesTaught1',
            'options' => array(
                'label' => 'Courses taught',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ReasonLeaving1',
            'options' => array(
                'label' => 'Reason for leaving',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Salary1',
            'options' => array(
                'label' => 'Final annual salary',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));        
        $this->add(array(
            'name' => 'S_Position2',
            'options' => array(
                'label' => '2. Position Title/Rank',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_StartDate2',
            'options' => array(
                'label' => 'Start Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_EndDate2',
            'options' => array(
                'label' => 'End Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_Empl2',
            'options' => array(
                'label' => 'Employer',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ImmedSupervisor2',
            'options' => array(
                'label' => 'Immediate Supervisor',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplAdd2',
            'options' => array(
                'label' => 'Employer Address',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplCity2',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplState2',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplZip2',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Responsibilities2',
            'options' => array(
                'label' => 'Responsibilities',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_CoursesTaught2',
            'options' => array(
                'label' => 'Courses taught',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ReasonLeaving2',
            'options' => array(
                'label' => 'Reason for leaving',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Salary2',
            'options' => array(
                'label' => 'Final annual salary',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));        
        $this->add(array(
            'name' => 'S_Position3',
            'options' => array(
                'label' => '3. Position Title/Rank',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_StartDate3',
            'options' => array(
                'label' => 'Start Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_EndDate3',
            'options' => array(
                'label' => 'End Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_Empl3',
            'options' => array(
                'label' => 'Employer',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ImmediateSupervisor3',
            'options' => array(
                'label' => 'Immediate Supervisor',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplAdd3',
            'options' => array(
                'label' => 'Employer Address',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplCity3',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplState3',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplZip3',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Responsibilities3',
            'options' => array(
                'label' => 'Responsibilities',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_CoursesTaught3',
            'options' => array(
                'label' => 'Courses taught',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ReasonLeaving3',
            'options' => array(
                'label' => 'Reason for leaving',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Salary3',
            'options' => array(
                'label' => 'Final annual salary',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));        
        $this->add(array(
            'name' => 'S_Position4',
            'options' => array(
                'label' => '4. Position Title/Rank',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_StartDate4',
            'options' => array(
                'label' => 'Start Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_EndDate4',
            'options' => array(
                'label' => 'End Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        $this->add(array(
            'name' => 'S_Empl4',
            'options' => array(
                'label' => 'Employer',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ImmedSupervisor4',
            'options' => array(
                'label' => 'Immediate Supervisor',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplAdd4',
            'options' => array(
                'label' => 'Employer Address',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplCity4',
            'options' => array(
                'label' => 'City',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplState4',
            'options' => array(
                'label' => 'State',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplZip4',
            'options' => array(
                'label' => 'Zip',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Responsibilities4',
            'options' => array(
                'label' => 'Responsibilities',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_CoursesTaught4',
            'options' => array(
                'label' => 'Courses taught',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_ReasonLeaving4',
            'options' => array(
                'label' => 'Reason for leaving',
            ),
            'attributes' => array(
                'type' => 'textarea',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_Salary4',
            'options' => array(
                'label' => 'Final annual salary',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Doct',
            'options' => array(
                'label' => 'Doctorate ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',                    
                ),
            ), 
            'attributes' => array(                     
                'required' =>  false,                
            ),    
        ));
        $this->add(array(
            'name' => 'S_DoctNameInstitut',
            'options' => array(
                'label' => 'Name of Institution',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_DegreeDoc',
            'options' => array(
                'label' => 'Degree Type <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getDegreetype(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_DegreeOtherDoc',
            'options' => array(
                'label' => 'If other, please specify',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        
        $this->add(array(
            'name' => 'S_DoctMajorField',
            'options' => array(
                'label' => 'Major Field of study',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        
        $this->add(array(
            'name' => 'S_DegreeSpecDoc',
            'options' => array(
                'label' => 'Specialization <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Mast',
            'options' => array(
                'label' => 'Masters ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_DegreeMast',
            'options' => array(
                'label' => 'Degree Type <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getDegreetype(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_DegreeOtherMast',
            'options' => array(
                'label' => 'If other, please specify',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_DegreeSpecMast',
            'options' => array(
                'label' => 'Specialization <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_MastNameInstitut',
            'options' => array(
                'label' => 'Name of Institution',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_MastMajorField',
            'options' => array(
                'label' => 'Major Field of study',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Bach',
            'options' => array(
                'label' => 'Bachelors ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_DegreeBach',
            'options' => array(
                'label' => 'Degree Type <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getDegreetype(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_DegreeOtherBach',
            'options' => array(
                'label' => 'If other, please specify',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_DegreeSpecBach',
            'options' => array(
                'label' => 'Specialization <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_BachNameInstitut',
            'options' => array(
                'label' => 'Name of Institution',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_BachMajorField',
            'options' => array(
                'label' => 'Major Field of study',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Assoc',
            'options' => array(
                'label' => 'Associates ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'S_DegreeAssoc',
            'options' => array(
                'label' => 'Degree Type <span class="required">*</span>',
                'required' => true,
                'value_options' =>$this->getDegreetype(),
            ),
            'attributes' => array(
              'class' => 'form-control',
            )
        ));
        $this->add(array(
            'name' => 'S_DegreeOtherAssoc',
            'options' => array(
                'label' => 'If other, please specify',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_DegreeSpecAssoc',
            'options' => array(
                'label' => 'Specialization <span class="required">*</span>',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        )); 
        $this->add(array(
            'name' => 'S_AssocNameInstitut',
            'options' => array(
                'label' => 'Name of Institution',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_AssocMajorField',
            'options' => array(
                'label' => 'Major Field of study',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));   
        $this->add(array(
            'name' => 'S_AppSign',
            'options' => array(
                'label' => 'Applicant name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_AppDate',
            'options' => array(
                'label' => 'Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'S_LastName',
            'options' => array(
                'label' => 'Last name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_FirstName',
            'options' => array(
                'label' => 'First name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_MiddleName',
            'options' => array(
                'label' => 'Middle name',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'S_EmplID',
            'options' => array(
                'label' => 'Employee ID',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Gender',
            'options' => array(
                'label' => 'Gender ',
                'value_options' => array(
                    'M'=>' Male',
                    'F' =>' Female',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Veteran',
            'options' => array(
                'label' => 'Are you a veteran? ',
                'value_options' => array(
                    'Y'=>' Yes',
                    'N' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Hispanic',
            'options' => array(
                'label' => 'Are you Hispanic or Latino/a (defined as an individual who identifies as having Hispanic, Latino/a, or Spanish origins, regardless of race, and/or also identifies as Cuban, Dominican, Mexican, Puerto Rican, or other Hispanic)? ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_AmerIndAlaskNativ',
            'options' => array(
                'label' => '1. American Indian or Alaska Native:  An individual having origins in any of the original peoples of North and South America (including Central America) AND who maintain tribal affiliation or community recognition. (Not Hispanic or Latino/a) ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_Asian',
            'options' => array(
                'label' => '2. Asian:  An individual having origins in any of the original peoples of the Far East, Southeast Asia, or Indian Subcontinent.  (Not Hispanic or Latino/a)',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        )); 
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_BlackAfricAmer',
            'options' => array(
                'label' => '3. Black or African American:  An individual having origins in any of the black racial groups of Africa. (Not Hispanic or Latino/a) ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_NativHawaiiPacIsland',
            'options' => array(
                'label' => '4. Native Hawaiian or other Pacific Islander: An individual having origins in any of the original people of Hawaii, Guam, Samoa, or other Pacific Islands. (Not Hispanic or Latino/a)',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_White',
            'options' => array(
                'label' => '5. White:  An individual having origins in any of the original peoples of Europe, North Africa, or the Middle East. (Not Hispanic or Latino/a) ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_TwoMoreRace',
            'options' => array(
                'label' => '6. Two or More of the Five Races Above. ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'S_NotDiscl',
            'options' => array(
                'label' => '7. Not Disclosed: ',
                'value_options' => array(
                    '1'=>' Yes',
                    '0' =>' No',                    
                ),
            ),            
        ));
        $this->add(array(
            'name' => 'S_DemoDate',
            'options' => array(
                'label' => 'Date',
            ),
            'attributes' => array(
                'type' => 'text',                  
                'required' => false,
                'class' => 'form-control date_pick',
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
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',                
		'class' => 'btn btn-large btn-primary',                
            ),
        ));
    }
    public function getPhoneTypeList() 
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM phone_type';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = 'Choose an item';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;  
    }
    public function getPreferredTeachingList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM teaching_schedule';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = 'Choose an item';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }      
    public function getPreferredTimeList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM teaching_time';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = 'Choose an item';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }   
    
    public function getDegreetype() {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM educationlistenum order by pkey ';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = '-- Select --';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }

    public function getStateList() {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM statelistlov order by pkey ';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = '-- Select --';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }
    public function getCountryList() {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM countrylov order by pkey ';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();          
        $selectData[] = '-- Select --';
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
       
         
}
?>



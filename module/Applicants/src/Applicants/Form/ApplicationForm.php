<?php
/*
 * Load Form based on university
 */
namespace Applicants\Form;
use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class ApplicationForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter,$domainName)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);
        parent::__construct('application');   
        
        $university = $this->getUniversityID($domainName);
        $formFields = $this->getFormFields($university);
        
        if(count($formFields)>0){
            foreach($formFields as $data){   
                if($data['section_id'] != '' && $data['section_dependent_column'] != '')
                {
                    $style = "display:none;"; 
                }
                else
                {
                    $style = "display:block;"; 
                }
                $this->_formFields[$data['position']] = array('section_name'=>$data['section'],'section_desc'=>$data['description'],'section_visible'=>$data['section_label_visible'],'position'=>$data['position'],'section_add_more'=>$data['section_add_more'],'section_id'=>$data['section_id'],'section_dependent_column'=>$data['section_dependent_column'],'section_display'=>$style,'section'=>$data['section']);
                foreach($data['fields'] as $field){
                    if($field['type']!=''){
                        $this->loadFormField($field);
                    }    
                }
            }
        }        
        $this->add(array(
            'name' => 'pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'JobListing_pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'PartyInfo_pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'is_saved',
            'attributes' => array(
                'type'  => 'hidden',
                'value' => '0',
            ),
        ));
        
        $this->add(array(
            'name' => 'InstructorTypeLOV_pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'AppStatusLOV_pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
                
        $this->add(array(
            'name' => 'submit-form',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Update',
                'id' => 'submitbutton',
		'class' => 'btn btn-large btn-primary',                
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
                'class' => 'form-control datepicker',
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
                'class' => 'form-control datepicker',                
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
    /**
     * Method to load form field
     * @param type $field
     */
    public function loadFormField($field){
        //$this->_formFields[] = $field['column'];   
        $column_name = $field['column'];
        if($field['add_more']=='yes'){
            $column_name = $field['column'].'[]';                        
        }
        
        $this->_formFields[$field['position']] = array(
                        'type' => $field['type'],
                        'column' => $column_name,
                        //'section' => $field['section'],
                        //'layout' => $field['layout'],
                        'position' => $field['position'],
                        'placeholder' => $field['placeholder'],
                        'dependent_column' => $field['dependent_column'],
                        //'description' => $field['description'],
                        //'section_label_visible' => $field['section_label_visible'],
                        'add_more' => $field['add_more'],
                        'section' => $field['section'],
                        );
        $required_label = '';
        $required = false;
        if($field['required']=='true'){
            $required_label = '*';
            $required = true;
        }
        $style = '';
        if($field['dependent_field']!='' && $field['dependent_field']!='0'){
            
            $dbAdapter = $this->getDbAdapter();                
            $sql = 'SELECT is_parentview FROM formfields WHERE pkey="'.$field['dependent_field'].'" ';
            $statement = $dbAdapter->query($sql);
            $result = $statement->execute();
            foreach ($result as $res) {
                $is_parentview = $res['is_parentview'];
            }
            if($is_parentview == '0')
            {
                $style = "display:none;";  
            }  
            /*else
            {
                $style = "display:none;";  
            }*/
        }
        if($field['type']=='text' && $column_name!='S_SocialSecurityNo'){                      
            $this->add(array(
                'name' => $column_name,
                'attributes' => array(
                    'type'  => 'text',
                    'required' => $required,
                    'class' => 'form-control-element', 
                    'placeholder' => $field['placeholder'],
                    'style' => $style,
                    'maxlength' => 5000,
                ),
                'options' => array(
                    'label' => $field['label'].$required_label,
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
            ));            
        }
        if($field['type']=='text' && $column_name=='S_SocialSecurityNo'){                        
            $this->add(array(
                'name' => $column_name,
                'attributes' => array(
                    'type'  => 'text',
                    'required' => $required,
                    'class' => 'form-control-element', 
                    'placeholder' => $field['placeholder'],
                    'style' => $style,
                    'maxlength' => 9,
                ),
                'options' => array(
                    'label' => $field['label'].$required_label,
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
            ));            
        }
        
        if($field['type']=='datefield'){                        
            $this->add(array(
                'name' => $column_name,
                'attributes' => array(
                    'type'  => 'text',
                    'required' => $required,
                    'class' => 'form-control-element datepicker', 
                    'placeholder' => $field['placeholder'],
                    'style' => $style,
                    'maxlength' => 10,
                ),
                'filters' => array(
                    'StringTrim',
                ),
                'options' => array(
                    'format' => 'YYYY-mm-dd',
                    'label' => $field['label'].$required_label,
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
            ));            
        }
        if($field['type']=='select'){
            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => $column_name,
                'options' => array(
                    'label' => $field['label'].$required_label,                    
                    'value_options' => $this->getSelectList($field['tablename']),
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
                'attributes' => array(                    
                    'class' => 'form-control-select',          
                    'required' => $required,
                    'style' => $style,
                )
            ));            
        }
        if($field['type']=='multiselect'){
            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => $column_name,
                'options' => array(
                    'label' => $field['label'].$required_label,                    
                    'value_options' => $this->getSelectList($field['tablename']),
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
                'attributes' => array(                 
                    'multiple' => 'multiple',
                    'class' => 'form-control-element',         
                    'required' => $required,
                    'style' => $style,
                )
            ));            
        }        
        if($field['type']=='radio'){
            $this->add(array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => $column_name,
                'options' => array(
                    'label' => $field['label'].$required_label,
                    'value_options' =>  $this->getRadioCheckOptions($field['pkey']),
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
                'attributes' => array(
                    'required' => $required,
                    'style' => $style,
                ),
            ));
        }
        if($field['type']=='checkbox'){
            $this->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => $column_name,
                'options' => array(
                    'label' => $field['label'].$required_label,
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),  
                'attributes' => array(
                    'required' => $required, 
                    'style' => $style,
                ),
            ));
        }
        if($field['type']=='textarea'){
            $this->add(array(
                'type' => 'textarea',
                'name' => $column_name,
                'options' => array(
                    'label' => $field['label'].$required_label,
                    'label_attributes' => array(
                        'style' => $style,
                    ),
                    'attributes' => array(
                        'add_more' => $field['add_more'],
                    ),
                ),
                'attributes' => array(                     
                    'required' => $required,
                    'class' => 'form-control-element',
                    'placeholder' => $field['placeholder'],
                    'style' => $style,
                 ),
            ));
        }
        if($field['type']=='hidden'){
            $this->add(array(
                'name' => $field['column'],
                'attributes' => array(
                    'type'  => 'hidden',
                ),
            ));
        }
    }

    /**
     * Method to get university id based on domain
     * @param type $domainName
     */
    public function getUniversityID($domainName){
        $university_id = '';
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT id FROM configure_university WHERE subdomain_name="'.$domainName.'" ';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        if(count($result)>0){
            foreach ($result as $res) {
                $university_id = $res['id'];
            }
        }
        return $university_id;        
    }
    /**
     * Method to get form fields..
     * @param type $university
     */
    public function getFormFields($university){
        $dbAdapter = $this->getDbAdapter();
        $sql = 'SELECT f.*,fs.pkey as section_id,fs.dependent_column as section_dependent_column,fs.name as section,fs.description,fs.is_label_visible,fs.is_add_more as section_add_more,fe.section_position,fe.field_position '
                . 'FROM formfields_editor fe '
                . 'LEFT JOIN formfields f ON f.pkey = fe.field_id '
                . 'JOIN formfields_section fs ON fs.pkey = fe.section_id '
                . 'WHERE fe.university = '.$university.' '
                . 'ORDER BY fe.field_position asc, fe.section_position asc';  
                
        //echo $sql;exit;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();        
        $selectData = array();
        if(count($result)>0){            
            foreach ($result as $res) {
                if($res['required']==1) {
                    $required = 'true';
                } else {
                    $required = 'false';
                }                 
                $selectData[$res['section_position']]['section'] = $res['section'];
                $selectData[$res['section_position']]['description'] = $res['description'];
                $selectData[$res['section_position']]['position'] = $res['section_position'];
                $selectData[$res['section_position']]['section_label_visible'] = $res['is_label_visible'];
                $selectData[$res['section_position']]['section_add_more'] = $res['section_add_more'];
                $selectData[$res['section_position']]['section_id'] = $res['section_id'];
                $selectData[$res['section_position']]['section_dependent_column'] = $res['section_dependent_column'];
                $selectData[$res['section_position']]['fields'][] = array(
                                                        'pkey' => $res['pkey'],
                                                        'label' => $res['label'],
                                                        'column' => $res['columns'], 
                                                        'type' => $res['type'],
                                                        'required' => $required,
                                                        'tablename' => $res['tablename'],                                                        
                                                        'placeholder' => $res['placeholder'],
                                                        'position' => $res['field_position'],
                                                        'dependent_column' => $res['dependent_column'],
                                                        'dependent_field' => $res['dependent_field'],    
                                                        'is_parentview' => $res['is_parentview'],
                                                        'add_more' => $res['is_add_more'], 
                                                        'section'  => $res['section'],
                                                    );                
            }
        }       
        return $selectData; 
        /*
        $dbAdapter = $this->getDbAdapter();
        $sql = 'SELECT f.*, fs.name as section, fs.description, fs.is_label_visible FROM formfields f '
                . 'JOIN formfields_university fu ON fu.formfields_pkey = f.pkey '
                . 'LEFT JOIN formfields_section fs ON fs.pkey = fu.section '
                . 'WHERE f.status = 1 AND fu.university="'.$university.'" '
                . 'ORDER BY fs.sortorder asc, f.sortorder asc';  
                       
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();  
        if(count($result)>0){
            $i = 0;
            foreach ($result as $res) {
                if($res['required']==1) {
                    $required = 'true';
                } else {
                    $required = 'false';
                }                                            
                $selectData[$i]['pkey'] = $res['pkey'];
                $selectData[$i]['label'] = $res['label'];
                $selectData[$i]['column'] = $res['columns'];
                $selectData[$i]['type'] = $res['type'];
                $selectData[$i]['required'] = $required;
                $selectData[$i]['tablename'] = $res['tablename'];
                $selectData[$i]['section'] = $res['section'];
                $selectData[$i]['description'] = $res['description'];
                $selectData[$i]['section_label_visible'] = $res['is_label_visible'];
                $selectData[$i]['layout'] = $res['layout'];
                $selectData[$i]['placeholder'] = $res['placeholder'];
                $i++;   
            }
        }        
        return $selectData;                
        */
    }
    /**
     * Method to get select list options
     * @param type $tablename
     */
    public function getSelectList($tablename){
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey,name FROM '.$tablename.' order by sortOrder asc';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        $selectData[''] = '-- Select --';
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData; 
    }
    /**
     * Method to get check/radio options value
     * @param type $form_pkey
     */
    public function getRadioCheckOptions($form_pkey){
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT rc_value, rc_label FROM formfields_radiocheck WHERE formfields_pkey = '.$form_pkey;        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();        
        foreach ($result as $res) {
            /*$isSelected = ($res['rc_value']=='N/A'? true : false);
            $selectData[] = array(
                'value' => $res['rc_value'],
                'label' => $res['rc_label'],
                'selected' => $isSelected,
            );*/
           $selectData[$res['rc_value']] = $res['rc_label'];
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
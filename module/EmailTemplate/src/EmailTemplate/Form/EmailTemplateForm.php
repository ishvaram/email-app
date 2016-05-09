<?php
namespace EmailTemplate\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EmailTemplateForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
	parent::__construct('EmailTemplate');		
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'pkey',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));	
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Template Name *',
            ),
        ));
        $this->add(array(
            'name' => 'from_name',
            'attributes' => array(
                'type'  => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'From Name *',
            ),
        ));
	$this->add(array(
            'name' => 'from_email',
            'attributes' => array(
                'type'  => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'From Email *',
            ),
        ));	
        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => 'Subject *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));		
        $this->add(array(
            'name' => 'body',
            'options' => array(
                'label' => 'Body Message *',
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => 6,        
                'class' => 'form-control',
                'id' => 'textarea-editor',
            ),
        ));	
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'status',
            'attributes' => array(
                'required' => true,
                'class' => 'form-control',
                'options' => array(
                    '0' => 'Inactive',
                    '1' => 'Activate',
                ),
            ),
            'options' => array(
                'label' => 'Status *',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Submit',
                'id' => 'submitbutton',
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
}
?>
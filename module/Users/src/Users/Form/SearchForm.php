<?php
namespace Users\Form;

use Zend\Form\Form;

class SearchForm extends Form
{
    public function __construct($name = null)
    {
        // modified by tfr, 10/12/2015 - we want to check that the $_REQUEST value
        // is defined before using it
        if(isset($_REQUEST['username'])){
            $username = $_REQUEST['username'];
        } else {
            $username = '';
        }
        if(isset($_REQUEST['email'])){
            $email = $_REQUEST['email'];
        } else {
            $email = '';
        }
        if(isset($_REQUEST['display_name'])) {
            $displayName = $_REQUEST['display_name'];
        } else {
            $displayName = '';
        }
        if(isset($_REQUEST['code'])){
            $code = $_REQUEST['code'];
        } else {
            $code = '';
        }
        if(isset($_REQUEST['company'])){
            $company = $_REQUEST['company'];
        } else {
            $company = '';
        }
        if(isset($_REQUEST['status'])){
            $status = $_REQUEST['status'];
        } else {
            $status = '';
        }
        
        // we want to ignore the name passed
        parent::__construct('users');		
        $this->setAttribute('method', 'post');		
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'form-control',
                'value' => $username,
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));		
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'form-control',
                'value' => $email,
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));		
        $this->add(array(
            'name' => 'display_name',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'form-control',
                'value' => $displayName,
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));		
        $this->add(array(
            'name' => 'code',
            'attributes' => array(
                'type'  => 'text',
                'required' => false,
                'class' => 'form-control',
                'value' => $code,
            ),
            'options' => array(
                'label' => 'Code',
            ),
        ));		
        $this->add(array(
            'name' => 'company',
            'options' => array(
                'label' => 'Company',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
                'value' => $company,
            ),
        ));		
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'state',
            'options' => array(
                'label' => 'Lead Type',
                'required' => false,
                'value_options' => array(
                                       '' => 'All',
                                        '1' => 'Active',
                                        '0' => 'In-Active',
                                ),
            ),
            'attributes' => array(
                 'class' => 'form-control',
                'value' => $status
            )
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
}
?>
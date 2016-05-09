<?php
namespace Users\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Users
{
    public $user_id;
    public $username;    
	public $inputFilter;
	
    public function exchangeArray($data)
    {
        $this->user_id     = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null; 
        $this->display_name = (isset($data['display_name'])) ? $data['display_name'] : null; 
        $this->email = (isset($data['email'])) ? $data['email'] : null;   	
        $this->password = (isset($data['password'])) ? $data['password'] : null; 
        $this->state = (isset($data['state'])) ? $data['state'] : null;  	
        $this->role = (isset($data['role'])) ? $data['role'] : null;  	        
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }
	
	// Add content to this method:
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $inputFilter->add(array(
                    'name'       => 'email',
                    'required'   => true,
                    'validators' => array(
                            array(
                                'name' => 'EmailAddress',
                                'options' => array(
                                    'message' => 'This is not a valid email address'
                                    )
                                ),
                    ),
            ));
			
            $inputFilter->add(array(
                'name'       => 'display_name',
                'required'   => true,
                'filters'    => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 128,
                        ),
                    ),
                ),
            ));
	$inputFilter->add(array(
                'name'       => 'role',
                'required'   => true,
                'filters'    => array(array('name' => 'Int')),                
            ));			
	 

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
	// Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
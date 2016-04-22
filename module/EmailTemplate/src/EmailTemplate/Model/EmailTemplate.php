<?php
namespace EmailTemplate\Model;

use Zend\InputFilter\Factory as InputFactory;     
use Zend\InputFilter\InputFilter;                 
use Zend\InputFilter\InputFilterInterface;        

class EmailTemplate
{
    public $inputFilter;
    
    public function exchangeArray($data)
    {
        foreach($data as $field=>$value):
            $this->$field = (isset($value))  ? $value  : null;
        endforeach;
    }
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
                'name'     => 'job',
                'required' => false,                
            )));
            
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
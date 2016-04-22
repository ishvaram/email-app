<?php
namespace Applicants\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;     
class Applicants
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
                'name'     => 'CountryLOV_pkey',
                'required' => false,                
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'StateListLov_pkey',
                'required' => false,                
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'isFelonyConvicted',
                'required' => false,                
            ))); 
            $inputFilter->add($factory->createInput(array(
                'name'     => 'isCurrentlyEmployed',
                'required' => false,                
            )));
             $inputFilter->add($factory->createInput(array(
                'name'     => 'isEverEmpInThisUniv',
                'required' => false,                
            )));
            $inputFilter->add($factory->createInput(array(
                'name'     => 'isAnyRelativesEmployedHere',
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

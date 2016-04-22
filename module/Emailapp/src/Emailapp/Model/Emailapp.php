<?php
namespace Emailapp\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Emailapp
{
   public function exchangeArray($data)
   {
        foreach($data as $field=>$value):
            $this->$field = (isset($value))  ? $value  : null;
        endforeach;
    }
    
    // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

}
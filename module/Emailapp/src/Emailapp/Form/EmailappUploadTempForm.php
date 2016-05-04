<?php
namespace Emailapp\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EmailappUploadTempForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
        parent::__construct('Emailapp');		
        $this->setAttribute('method', 'post');	
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
                'required' => true,
                'onchange' =>'checkFile(this)',
            ),
            'options' => array(
                'label' => 'Select File',
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
<?php
namespace Emailapp\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EmailappForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
        parent::__construct('Emailapp');		
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'curic_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));	
        $this->add(array(
            'name' => 'curic_code',
            'options' => array(
                'label' => 'Code *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'curic_title',
            'options' => array(
                'label' => 'Title *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'curic_desc',
            'options' => array(
                'label' => 'Description ',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'curic_version',
            'options' => array(
                'label' => 'Version *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
                'onKeyPress' =>'return isNumberKeyConfig(event)',
                'maxlength'=>'3'
            ),
        ));
        $this->add(array(
            'name' => 'curic_url',
            'options' => array(
                'label' => 'URL ',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'curic_date',
            'options' => array(
                'label' => 'Date ',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => false,
                'class' => 'form-control',
                'placeholder' => 'yyyy-mm-dd',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'curic_type',
            'id' => 'curic_type',
            'attributes' => array(
                    'required' => false,
                    'class' => 'form-control',
                    'options' => $this->getTypeList(),     
            ),
            'options' => array(
                'label' => 'Emailapp Type *',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'curic_class',
            'id' => 'curic_class',
            'attributes' => array(
                    'required' => false,
                    'class' => 'form-control',
                    'options' => $this->getClassList(),     
            ),
            'options' => array(
                'label' => 'Emailapp Class *',
            ),
        ));
	
	          
	$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Add New',
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
   
   public function getTypeList()
   {
       $dbAdapter = $this->getDbAdapter();                
       $sql = 'SELECT p.pkey, p.name FROM emailapp_type as p where p.pkey != 0 ORDER BY p.pkey ASC';        
       $statement = $dbAdapter->query($sql);
       $result = $statement->execute();
       $selectData = array();
       $selectData[''] = 'Select Type';
       foreach ($result as $res) {
           $selectData[$res['pkey']] = ucwords($res['name']);
       }
       return $selectData;                
   }
   
   public function getClassList()
   {
       $dbAdapter = $this->getDbAdapter();                
       $sql = 'SELECT p.pkey, p.name FROM emailapp_class as p where p.pkey != 0 ORDER BY p.pkey ASC';        
       $statement = $dbAdapter->query($sql);
       $result = $statement->execute();
       $selectData = array();
       $selectData[''] = 'Select Class';
       foreach ($result as $res) {
           $selectData[$res['pkey']] = ucwords($res['name']);
       }
       return $selectData;                
   }
}
?>
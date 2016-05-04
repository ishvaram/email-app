<?php
namespace Emailapp\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EmailappEditForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
        parent::__construct('Emailapp');		
        $this->setAttribute('method', 'post');		
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));	
        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => 'Name *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email *',
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
        ));
        $this->add(array(
            'name' => 'content',
            'options' => array(
                'label' => 'Content *',
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => 6,        
                'class' => 'form-control',
                'id' => 'textarea-editor',
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
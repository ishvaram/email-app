<?php
namespace Emailapp\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class EmailappSendmailForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
        parent::__construct('Emailapp');		
        $this->setAttribute('method', 'post');	
        $this->setAttribute('enctype','multipart/form-data');
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'template_name',
            'attributes' => array(
                            'required' => true,
                            'class' => 'form-control',
                            'options' => $this->getTemplateList(),  
                            'onChange' => 'getTempDetails(this.value)', 
                            'id'=> 'template' 
                         ),
            'options' => array(
                'label' => 'Template Name *',
            ),
        ));
        
        $this->add(array(
            'name' => 'import_date',
            'attributes' => array(
                'type'  => 'text',
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Imported Date *',
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
                'style'=> ''
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
   
   public function getTemplateList()
    {
        $dbAdapter = $this->getDbAdapter();                
        $sql = 'SELECT pkey, name FROM email_template where status = 1 ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();
        $selectData['empty'] = 'Select Template';
        foreach ($result as $res) {
            $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData;
    }    
}
?>
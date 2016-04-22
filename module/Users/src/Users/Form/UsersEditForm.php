<?php
namespace Users\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class UsersEditForm extends Form
{
    public function __construct(AdapterInterface $dbAdapter)
    {
        // we want to ignore the name passed
        $this->setDbAdapter($dbAdapter);		
        parent::__construct('Users');		
        $this->setAttribute('method', 'post');		
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'readonly' => true,
                'required' => true,
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'User Name *',
            ),
        ));
        $this->add(array(
            'name' => 'display_name',
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
                'readonly' => true,
                'required' => true,
                'class' => 'form-control',				
            ),
        ));
	$this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
            'attributes' => array(
                'type' => 'password',
                'required' => false,
                'class' => 'form-control',
            ),
        ));		
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'state',
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
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'role',
            'id' => 'role',
            'attributes' => array(
                    'required' => true,
                    'class' => 'form-control',
                    'options' => $this->getRoleList(),
                    'onChange' => 'checkRole()',
            ),
            'options' => array(
                'label' => 'Role *',
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
 
   public function getRoleList()
   {
       $dbAdapter = $this->getDbAdapter();                
       $sql = 'SELECT r.id, r.role_id as role FROM user_role as r ORDER BY r.id ASC';        
       $statement = $dbAdapter->query($sql);
       $result = $statement->execute();
       $selectData = array();
       $selectData[''] = 'Select Role';
       foreach ($result as $res) {
            $selectData[$res['id']] = ucwords($res['role']);
       }
       return $selectData;                
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
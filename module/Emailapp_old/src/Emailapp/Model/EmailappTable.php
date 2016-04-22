<?php
namespace Emailapp\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\DriverInterface;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\Expression as Expr;
use Zend\Crypt\Password\Bcrypt;

class EmailappTable
{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;		
    }
    public function fetchAll()
    {	
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
    
    public function searchBy($post)
    {		
        $adapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('c' => 'email_list'));                  
        $select->order(array('c.id DESC')); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;		
    }

    public function getEmailapp($id)
    {		
        $id  = (int) $id;        
        $select = $this->tableGateway->getSql()->select()
                ->where(array('email_list.id = ?' => $id));        
        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
	//	print_r($rowset); exit;
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
	return $row;
    }

    public function gettemplateDetails($tempid) {
        $tempid  = (int) $tempid;        
        
        $adapter = $this->tableGateway->getAdapter();
        $mail_sent = date('Y-m-d H:i:s');
        $sql = "SELECT * from email_template where pkey = '".$tempid."' "; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        $row = $result->current();
        
	return $row;
    }
    
    public function saveEmailapp(Emailapp $Emailapp)
    {	
       // echo '<pre>'; print_r($School);exit;
        $id = (int)$Emailapp->curic_id;			
        if ($id == 0) {
            $data = array(
                'curic_code' => $Emailapp->curic_code,  
                'curic_title' => $Emailapp->curic_title,
                'curic_desc' => $Emailapp->curic_desc,
                'curic_version' => $Emailapp->curic_version,
                'curic_url' => $Emailapp->curic_url,
                'curic_date' => $Emailapp->curic_date,
                'curic_type' => $Emailapp->curic_type,
                'curic_class' => $Emailapp->curic_class,
            );
            $this->tableGateway->insert($data);			
            $dbAdapter = $this->tableGateway->adapter;
        }
        else {
            $data = array(
                'curic_code' => $Emailapp->curic_code,  
                'curic_title' => $Emailapp->curic_title,
                'curic_desc' => $Emailapp->curic_desc,
                'curic_version' => $Emailapp->curic_version,
                'curic_url' => $Emailapp->curic_url,
                'curic_date' => $Emailapp->curic_date,
                'curic_type' => $Emailapp->curic_type,
                'curic_class' => $Emailapp->curic_class,
            );
            $this->tableGateway->update($data, array('curic_id' => $id));
        }
    }
   	
    public function deleteEmailapp($id)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	             
        $this->tableGateway->delete(array('id' => $id));       
    }
    
    // Import data to coursesdef table 
    public function saveEmailappImport($Emailapp)
    {
        foreach($Emailapp as $key=>$value)
        {
            $data[$key] = $Emailapp[$key];
        }
        $this->tableGateway->insert($data);			
        $dbAdapter = $this->tableGateway->adapter;
        $id = $this->tableGateway->lastInsertValue;
        //$this->insertCourseDef($data['name'],$data['code'],$data['Partners_pkey'],$data['description']);
        return $id;
    }
    
    public function UpdateEmailapp($id) {
        
        $adapter = $this->tableGateway->getAdapter();
        $mail_sent = date('Y-m-d H:i:s');
        $sql = "UPDATE email_list SET mail_sent = '".$mail_sent."' where id = '".$id."' "; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
    }

}

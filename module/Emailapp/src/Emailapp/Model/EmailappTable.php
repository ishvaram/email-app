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
        $select->from(array('c' => 'email_content'));                  
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
        $adapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('c' => 'email_content'));                  
        $select->where(array('c.id = ?' => $id));  
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Could not find row $pkey");
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
    
    public function saveEmailappEdit($Emailapp)
    {
        $adapter = $this->tableGateway->getAdapter();
        $sampleTable = new TableGateway('email_content', $adapter, null,new HydratingResultSet());
        
        $id = (int)$Emailapp->id;               
        $data = array(
                'name' => $Emailapp->name,
                'email' => $Emailapp->email, 
                'content' => $Emailapp->content,
            );
        $sampleTable->update($data, array('id' => $id));
        return true;
    } 
    
    public function deleteEmailapp($id)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	             
        $this->tableGateway->delete(array('id' => $id));       
    }
    
    // Import data to email_list table 
    public function saveEmailappImport($Emailapp)
    {
        foreach($Emailapp as $key=>$value)
        {
            $data[$key] = $Emailapp[$key];
        }
        $this->tableGateway->insert($data);			
        $dbAdapter = $this->tableGateway->adapter;
        $id = $this->tableGateway->lastInsertValue;
        //Insert in to Temp table
        $adapter = $this->tableGateway->getAdapter();		
        $sampleTable = new TableGateway('email_list_temp', $adapter, null,new HydratingResultSet());
        $sampleTable->insert(array(
            'org_admin' => $data['org_admin'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'org_id' => $data['org_id'],
            'deployment_name' => $data['deployment_name'],
            'deployment_org_region' => $data['deployment_org_region'],
            'maintenance_start' => $data['maintenance_start'],
            'maintenance_end' => $data['maintenance_end'],
            'sandbox_region' => $data['sandbox_region'],
            'sandbox_maintenance_start' => $data['sandbox_maintenance_start'],
            'sandbox_maintenance_end' => $data['sandbox_maintenance_end'],
            'import_date' => $data['import_date'],
            ));
        return $id;
    }
    
    // Import data to email_list table 
    public function saveEmailappImportTemp($Emailapp)
    {
        foreach($Emailapp as $key=>$value)
        {
            $data[$key] = $Emailapp[$key];
        }
        //Insert in to Temp table
        $adapter = $this->tableGateway->getAdapter();
        $sampleTable = new TableGateway('email_content', $adapter, null,new HydratingResultSet());
        $sampleTable->insert(array(
            'name' => $data['name'],
            'email' => $data['email'],
            'content' => $data['content'],
            'import_date' => $data['import_date'],
            'mail_sent' => $data['mail_sent'],
            ));
        return true;
    }
    
    
    public function saveEmailapp() {
        $adapter = $this->tableGateway->getAdapter();
        $mail_sent = date('Y-m-d H:i:s');
        $sql = "SELECT * from email_list_temp group by email asc"; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        foreach ($result as $res) {
            //Insert in to Converted table
            $content = "<table cellpadding='5' cellspacing='0' style='border: solid 1px #000; border-collapse: collapse'>"
            . "<tr style='background:#f89829; text-align:center; vertical-align:bottom;'>"
            . "<th style='border: solid 1px #000; border-collapse: collapse; text-align:center; vertical-align:bottom;'>Production Org ID</th>"
            . "<th style='border: solid 1px #000; text-align:center; vertical-align:bottom;'>Org Name</th>"
            . "<th style='border: solid 1px #000; text-align:center; vertical-align:bottom;'>Prod Maintenance Start</th>"
            . "<th style='border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;'>Prod Maintenance End</th>"
            . "<th style='border: solid 1px #000; text-align:center; vertical-align:bottom;'>Full Sandbox Maintenance Start</th>"
            . "<th style='border: solid 1px #000; border-collapse: collapse text-align:center; vertical-align:bottom;'>Full Sandbox Maintenance End</th>"
            . "</tr>";
        
            $sql1 = "SELECT count(*) as emailcount, email from email_list_temp where email = '".$res['email']."'"; 
            $statement1 = $adapter->query($sql1);
            $result1 = $statement1->execute();
            foreach ($result1 as $res1) {
                //echo $res1['email'].':::::'.$res1['emailcount'].'<br>';
                $sql2 = "SELECT * from email_list_temp where email = '".$res1['email']."'"; 
                $statement2 = $adapter->query($sql2);
                $result2 = $statement2->execute();
                foreach ($result2 as $res2) {
                $content .= "<tr><td style='border: solid 1px #000; border-collapse: collapse'>".$res2['org_id']."</td>"
                . "<td style='border: solid 1px #000; border-collapse: collapse'>".$res2['deployment_name']."</td>"
                . "<td style='border: solid 1px #000; border-collapse: collapse'>".$res2['maintenance_start']."</td>"
                . "<td style='border: solid 1px #000; border-collapse: collapse'>".$res2['maintenance_end']."</td>"
                . "<td style='border: solid 1px #000; border-collapse: collapse'>".$res2['sandbox_maintenance_start']."</td>"
                . "<td style='border: solid 1px #000; border-collapse: collapse'>".$res2['sandbox_maintenance_end']."</td>"
                . "</tr>"; 
                }
            }
        $content .= "</table>";	
        $sampleTable = new TableGateway('email_content', $adapter, null,new HydratingResultSet());
        $sampleTable->insert(array(
            'name' => $res['firstname'].' '.$res['lastname'],
            'email' => $res['email'],
            'content' => $content,
            'import_date' => $res['import_date'],
            ));
        }
        $sql3 = "TRUNCATE email_list_temp"; 
        $statement3 = $adapter->query($sql3);
        $result3 = $statement3->execute();
        return true;
        
    }
    
    public function UpdateEmailapp($id) {
        $adapter = $this->tableGateway->getAdapter();
        $mail_sent = date('Y-m-d H:i:s');
        $sql = "UPDATE email_content SET mail_sent = '".$mail_sent."' where id = '".$id."' "; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
    }
    
    public function getMailContent($email) {
        $adapter = $this->tableGateway->getAdapter();        
        $sql = "SELECT * from email_content where email='".$email."'"; 
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        $row = $resultSet->current();
        return $row;
    }

}

<?php
namespace EmailTemplate\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Expression as Expr;

class EmailTemplateTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;		
    }    
    public function fetchAll()
    {	
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();        
        $select->from(array('e' => 'email_template')); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        return $resultSet;        
    }
    public function saveTemplate(EmailTemplate $template)
    {
        $pkey = (int)$template->pkey;               
        $job = implode(",",$template->job);
        if ($pkey == 0) {
            $data = array(
                'name' => $template->name,               
                'from_name' => $template->from_name,
                'from_email' => $template->from_email, 
                'subject' => $template->subject,
                'body' => $template->body,
                'status' => $template->status,                     
            );
            $this->tableGateway->insert($data);			            
        }
        else {
            $data = array(
                'name' => $template->name,        
                'from_name' => $template->from_name,
                'from_email' => $template->from_email, 
                'subject' => $template->subject,
                'body' => $template->body,
                'status' => $template->status,	 
            );
            $this->tableGateway->update($data, array('pkey' => $pkey));
        }
    }  
    public function getTemplate($pkey)
    {		
        $pkey  = (int) $pkey;   
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();        
        $select->from(array('e' => 'email_template')); 
        $select->where->equalTo("e.pkey", $pkey);
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
    public function deleteTemplate($pkey)
    {
        $this->tableGateway->delete(array('pkey' => $pkey));               
    }
    
}

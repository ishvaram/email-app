<?php
namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Adapter\Driver\DriverInterface;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Predicate\Expression as Expr;
use Zend\Crypt\Password\Bcrypt;

class UsersTable
{
    protected $tableGateway;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;		
    }
    public function fetchAll()
    {	
        $select = $this->tableGateway->getSql()->select()->join('user_role_linker', 'user.user_id=user_role_linker.user_id',array())->where("user_role_linker.role_id != 0");		
        $resultSet = $this->tableGateway->selectWith($select);		
        return $resultSet;
    }	
    public function searchBy($post, $search)
    {		
        $adapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('u' => 'user'))
                   ->join(array('url' => 'user_role_linker'),'u.user_id = url.user_id',array('role_id'), 'left')
                  ->join(array('ur' => 'user_role'),'url.role_id = ur.id',array('role_name'=>'role_id'), 'left')                 
                  ->join(array('up' => 'user_partyinfo_linker'), 'up.user_id = u.user_id', array(), 'left')
                  ->join(array('pr' => 'partyinfohasroles'), 'pr.PartyInfo_pkey = up.partyInfo_pkey', array(), 'left')  
                 ->join(array('p' => 'partners'), 'p.pkey = pr.Partners_pkey', array('university' => 'name'), 'left')  ;

        if(isset($post['display_name'])) {	
            $select->where->like('u.display_name', "%".trim($post['display_name'])."%");					
        }
        if(isset($post['email'])) {	
            $select->where->like('u.email', "%".trim($post['email'])."%");					
        }
        if((isset($post['username']))) {	
            $select->where->like('u.username', "%".trim($post['username'])."%");					
        }        
         //Search Term
        if($search!=''){
            $search = urldecode($search);
            $search_cond = array();            
            $search_cond[] = 'u.display_name like "%'.$search.'%"';
            $search_cond[] = 'u.email like "%'.$search.'%"';
            $search_cond[] = 'u.username like "%'.$search.'%"';
            $search_cond[] = 'ur.role_id like "%'.$search.'%"';
            $search_cond[] = 'p.name like "%'.$search.'%"';
            $select->where("(".implode(" OR ",$search_cond).")");
        }
        $select->order(array('u.user_id DESC')); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $resultSet->buffer();
        return $resultSet;		
    }
    public function getUsers($id)
    {	
        
        $id  = (int) $id;        
        $select = $this->tableGateway->getSql()->select()
                                ->join('user_role_linker', 'user.user_id=user_role_linker.user_id',array('role' => 'role_id'))                               
                                ->where(array('user.user_id = ?' => $id));        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
		
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
	//echo '$row'; exit;	
        return $row;
    }

    public function saveUsers(Users $Users)
    {	
        $bcrypt = new Bcrypt;
        if($Users->password)
        {
                $securePass = $bcrypt->create($Users->password);
        }		
        $id = (int)$Users->user_id;			
        if ($id == 0) {
            $data = array(
                    'username' => $Users->username, 
                    'email' => $Users->email,  	
                    'password' => $securePass,
                    'display_name' => $Users->display_name,
                    'state'	 => $Users->state,
            );
            $this->tableGateway->insert($data);			
            $dbAdapter = $this->tableGateway->adapter;
            $lastId = $dbAdapter->getDriver()->getConnection()->getLastGeneratedValue();
            $this->saveUserRole($lastId, $Users->role);			
        } else {
            if ($this->getUsers($id)) {
                if(isset($securePass)) { 
                    $data = array(
                            'username' => $Users->username, 
                            'email' => $Users->email,
                            'password' => $securePass,
                            'display_name' => $Users->display_name,  
                            'state'	 => $Users->state,
                    );
                } else {
                    $data = array(
                            'username' => $Users->username, 
                            'email' => $Users->email,
                            'display_name' => $Users->display_name,  
                            'state'	 => $Users->state,
                    );
                }
                $this->tableGateway->update($data, array('user_id' => $id));
                $this->updateUserRole($id, $Users->role);
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
		
    }
		
    public function deleteUsers($id)
    {
        $this->tableGateway->delete(array('user_id' => $id));
        $this->deleteUserRole($id);       
    }
	
    public function saveUserRole($lastId, $role_id)
    {		
        $adapter = $this->tableGateway->getAdapter();		
        $sampleTable = new TableGateway('user_role_linker', $adapter, null,new HydratingResultSet());		
        $sampleTable->insert(array(
                 'user_id' => $lastId,
                 'role_id' => $role_id
        ));

        return true;
    }	
    public function updateUserRole($lastId, $role_id)
    {		
        $adapter = $this->tableGateway->getAdapter();		
        $sampleTable = new TableGateway('user_role_linker', $adapter, null,new HydratingResultSet());		
        $sampleTable->update(array(
                 'user_id' => $lastId,
                 'role_id' => $role_id
        ), array('user_id' => $lastId));

        return true;
    }
    public function deleteUserRole($userid)
    {		
            $adapter = $this->tableGateway->getAdapter();		
            $sampleTable = new TableGateway('user_role_linker', $adapter, null,new HydratingResultSet());		
            $sampleTable->delete(array('user_id' => $userid));

            return true;
    }		
}

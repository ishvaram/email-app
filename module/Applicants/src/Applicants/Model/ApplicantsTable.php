<?php
namespace Applicants\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Predicate\Expression as Expr;

class ApplicantsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;		
    }
    //get enabled filters
    public function getEnabledColumns()
    {       
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = "select so.pkey,ote.name,ote.label,ote.table,ote.column,so.sort_order
                from siteoptions as so
                join optiontypeenum as ote
                on (ote.pkey = so.OptionTypeEnum_pkey)
                where so.enable = 1
                and ote.OptionCategoryTypeEnum_pkey = 3
                order by so.sort_order asc";
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $columnsData = array();
        $columnsList = array();
        foreach ($result as $res) {
            $columnsList[] = $res['label'];
            $columnsData[] = array(
                'id'=>$res['pkey'],
                'name' => $res['name'],
                'label' => $res['label'],
                'table' => $res['table'],
                'column' => $res['column'],
            );
        }

        $returnOutput = array(
            'data' => $columnsData,
            'list' => $columnsList,
        );
        return $returnOutput;
    }
    /*
    * Method to fetch applicants
    */
    public function getSearchResult($post,$status,$search)
    {        
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $records = array();     
        if(((isset($post['university']) && !empty($post['university'])) || (isset($post['job']) && !empty($post['job'])) || (isset($post['instructor_type']) && !empty($post['instructor_type'])) || (isset($post['highest_degree']) && !empty($post['highest_degree'])) || (isset($post['state']) && !empty($post['state'])) || (isset($post['application_status']) && !empty($post['application_status'])) || (isset($post['course']) && !empty($post['course'])) || (isset($post['application_from_date']) && isset($post['application_to_date']) && !empty($post['application_from_date']) && !empty($post['application_to_date']))) || $status!='')
        {
            $select = $sql->select();
            $select->columns(array('pkey','firstname','lastname','email','AppliedDate','notes'));
            $select->from(array('c' => 'newapplication'));
            $select->join(array('p' => 'partyinfo'), 'p.pkey = c.PartyInfo_pkey',array(),'left');
            $select->join(array('ptrole' => 'partyinfohasroles'), 'ptrole.PartyInfo_pkey = p.pkey',array(),'left');
            $select->join(array('ptstatus' => 'partyinfohasstatus'), 'ptstatus.PartyInfo_pkey = p.pkey',array(),'left');
            //$select->join(array('a' => 'applicantstatuslov'), 'a.pkey = ptstatus.ApplicantStatusLov_pkey',array(),'left');
            $select->join(array('ptenum' => 'partytypeenum'), 'ptenum.pkey = ptrole.PartyTypeEnum_pkey',array(),'left');
            $select->join(array('job' => 'joblisting'), 'job.pkey = c.JobListing_pkey',array('jobname'=>'name'),'left');
            $select->join(array('pa' => 'partners'), 'pa.pkey = job.Partners_pkey',array('partnersName'=>'name'),'left');
            $select->join(array('i' => 'instructortypelov'), 'i.pkey = c.InstructorTypeLOV_pkey',array('instructorType'=>'name'),'left');
            $select->join(array('e' => 'educationlistenum'), 'e.pkey = c.EducationListEnum_pkey',array('highest_degree' => 'name'),'left');
            $select->join(array('state' => 'statelistlov'), 'state.pkey = c.StateListLov_pkey ',array(),'left');            
            $select->join(array('appstatus' => 'appstatuslov'), 'appstatus.pkey = c.AppStatusLOV_pkey', array('app_status'=>'name'),'left');
            //$select->join(array('appgroup' => 'appstatusgroup'), 'appgroup.pkey = appstatus.parent', array('app_status'=>new Expr("CASE WHEN (appgroup.name!='') THEN appgroup.name ELSE appstatus.name END")),'left');
            $select->where("c.AppStatusLOV_pkey!=15");
            //$select->where("c.AppStatusLOV_pkey <>  '100' AND  a.code in ('110','140') AND ptenum.code = '150' ");
            //$select->where("ptenum.code = '150' ");
            if(isset($post['university']))
            {   
                $select->where("pa.pkey IN ('".implode("','", $post['university']). "') ");
            }
            if(isset($post['job']))
            {   
                $select->where("job.pkey IN ('".implode("','", $post['job']). "') ");
            }
            if(isset($post['instructor_type']))
            {
                $select->where("i.pkey IN ('".implode("','", $post['instructor_type']). "')");
            }
            if(isset($post['highest_degree']))
            {              
                $select->where('e.pkey IN ("'.implode('","', $post['highest_degree']). '")');
            }
            if(isset($post['state']))
            {   
                $select->where("state.pkey IN ('".implode("','", $post['state']). "')");
            }
            if(isset($post['application_status']))
            {
                $select->where("appstatus.pkey IN ('".implode("','", $post['application_status']). "')");
            }  
            if(isset($post['application_from_date']) && $post['application_from_date']!='' && isset($post['application_to_date']) && $post['application_to_date']!='')
            {
                $start_date = date("Y-m-d", strtotime($post['application_from_date']));			
                $end_date = date('Y-m-d', strtotime($post['application_to_date']));                
                $select->where("c.appliedDate BETWEEN '".$start_date."' AND '".$end_date."'");
            }
            if(isset($post['course']))
            {
                $course_ids = implode(",",$post['course']);
                //$select->where("c.PartyInfo_pkey IN (SELECT partyinfocour.PartyInfo_pkey FROM partyinfohascourses as partyinfocour WHERE partyinfocour.CourseDef_pkey IN($course_ids)) ");
                $select->join(array('partyinfocour' => 'partyinfohascourses'), 'partyinfocour.PartyInfo_pkey = c.PartyInfo_pkey ',array(),'');                
                $select->where("partyinfocour.CourseDef_pkey IN($course_ids)");                
            }
            if($status!='')
            {
                $select->where("appstatus.name IN ('".urldecode($status). "')");
            }   
            //Search Term
            if($search!=''){
                $search = urldecode($search);
                $search_cond = array();
                $search_cond[] = 'c.firstname like "%'.$search.'%"';
                $search_cond[] = 'c.lastname like "%'.$search.'%"';
                $search_cond[] = 'c.email like "%'.$search.'%"';
                $search_cond[] = 'e.name like "%'.$search.'%"';
                $search_cond[] = 'job.name like "%'.$search.'%"';   
                $search_cond[] = 'appstatus.name like "%'.$search.'%"';
                $select->where("(".implode(" OR ",$search_cond).")");
            }            
            $select->group('c.pkey');
            $select->order(array('c.pkey DESC'));            
            $statement = $sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet = new ResultSet();
            $resultSet->initialize($result);  
            $resultSet->buffer();
            return $resultSet;
        }
        
        /*if(count($resultSet)>0)
        {   
            foreach ($resultSet as $res)
            {
                $records[] = $res;
            }    
        }
        return $records;   */     	
    } 
    /**
     * Method to list applicants applied for multiple jobs
     * 
     */       
    public function getApplicantMultipleJobs($post,$status)
    {   
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $records = array();     
        if(((isset($post['university']) && !empty($post['university'])) || (isset($post['instructor_type']) && !empty($post['instructor_type'])) || (isset($post['highest_degree']) && !empty($post['highest_degree'])) || (isset($post['state']) && !empty($post['state'])) || (isset($post['application_status']) && !empty($post['application_status'])) || (isset($post['course']) && !empty($post['course'])) || (isset($post['application_from_date']) && isset($post['application_to_date']) && !empty($post['application_from_date']) && !empty($post['application_to_date']))) || $status!='')
        {
            $select = $sql->select();
            $select->columns(array('email','num'=>new Expr('count(DISTINCT c.pkey)')));
            $select->from(array('c' => 'newapplication'));
            $select->join(array('p' => 'partyinfo'), 'p.pkey = c.PartyInfo_pkey',array(),'');
            //$select->join(array('ptrole' => 'partyinfohasroles'), 'ptrole.PartyInfo_pkey = p.pkey',array(),'');
            //$select->join(array('ptstatus' => 'partyinfohasstatus'), 'ptstatus.PartyInfo_pkey = p.pkey',array(),'inner');
            //$select->join(array('a' => 'applicantstatuslov'), 'a.pkey = ptstatus.ApplicantStatusLov_pkey',array(),'inner');
            //$select->join(array('ptenum' => 'partytypeenum'), 'ptenum.pkey = ptrole.PartyTypeEnum_pkey',array(),'inner');
            //$select->join(array('ptenum' => 'partytypeenum'), 'ptenum.pkey = ptrole.PartyTypeEnum_pkey',array(),'left');
            $select->join(array('job' => 'joblisting'), 'job.pkey = c.JobListing_pkey',array(),'left');
            $select->join(array('pa' => 'partners'), 'pa.pkey = job.Partners_pkey',array('partnersName'=>'name'),'left');
            $select->join(array('i' => 'instructortypelov'), 'i.pkey = c.InstructorTypeLOV_pkey',array(),'left');
            $select->join(array('e' => 'educationlistenum'), 'e.pkey = c.EducationListEnum_pkey',array(),'left');
            $select->join(array('state' => 'statelistlov'), 'state.pkey = c.StateListLov_pkey ',array(),'left');
            $select->join(array('appstatus' => 'appstatuslov'), 'appstatus.pkey = c.AppStatusLOV_pkey', array(),'left');
           // $select->where("c.AppStatusLOV_pkey <>  '100' AND  a.code in ('110','140') AND ptenum.code = '150' ");
            //$select->where("c.AppStatusLOV_pkey!=15"); 
            if(isset($post['university']))
            {   
                $select->where("pa.pkey IN ('".implode("','", $post['university']). "') ");
            }
            if(isset($post['instructor_type']))
            {
                $select->where("i.pkey IN ('".implode("','", $post['instructor_type']). "')");
            }
            if(isset($post['highest_degree']))
            {              
                $select->where('e.pkey IN ("'.implode('","', $post['highest_degree']). '")');
            }
            if(isset($post['state']))
            {   
                $select->where("state.pkey IN ('".implode("','", $post['state']). "')");
            }
            if(isset($post['application_status']))
            {
                $select->where("appstatus.pkey IN ('".implode("','", $post['application_status']). "')");
            }  
            if(isset($post['application_from_date']) && $post['application_from_date']!='' && isset($post['application_to_date']) && $post['application_to_date']!='')
            {
                $start_date = date("Y-m-d", strtotime($post['application_from_date']));			
                $end_date = date('Y-m-d', strtotime($post['application_to_date']));                
                $select->where("c.appliedDate BETWEEN '".$start_date."' AND '".$end_date."'");
            }
            if($status!='')
            {
                $select->where("appstatus.name IN ('".urldecode($status). "')");
            }   
            if(isset($post['course']))
            {
                $course_ids = implode(",",$post['course']);
                //$select->where("c.PartyInfo_pkey IN (SELECT partyinfocour.PartyInfo_pkey FROM partyinfohascourses as partyinfocour WHERE partyinfocour.CourseDef_pkey IN($course_ids)) ");
                $select->join(array('partyinfocour' => 'partyinfohascourses'), 'partyinfocour.PartyInfo_pkey = c.PartyInfo_pkey ',array(),'');                
                $select->where("partyinfocour.CourseDef_pkey IN($course_ids)"); 
            }
            $select->group(array('c.email','c.partnersName'));
            $select->having('num >1');            
            //echo $select->getSqlString();
            $statement = $sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet = new ResultSet();
            $resultSet->initialize($result);            
            if(count($resultSet)>0)
            {   
                foreach ($resultSet as $res)
                {
                    $records[$res->partnersName][] = $res->email;
                }    
            }            
        }         
        return $records;        	
    }
    
    public function getApplicant($pkey)
    {
        $pkey  = (int) $pkey;
        $select = $this->tableGateway->getSql()->select()
                    ->join(array('i'=>'instructortypelov'), 'i.pkey = newapplication.InstructorTypeLOV_pkey',array('instructor_type_name' => 'name'))
                    ->join(array('j'=>'joblisting'), 'j.pkey = newapplication.JobListing_pkey',array('job_name' => 'name'))
                    ->join(array('pa'=>'partners'), 'pa.pkey = j.Partners_pkey',array('partners_name' => 'name', 'partners_key' => 'pkey', 'domainName'))
                    ->join(array('s'=>'school'), 's.pkey = j.School_pkey',array('school_name' => 'name'))
                    ->join(array('r'=>'religionlov'), 'r.pkey = newapplication.ReligionLOV_pkey',array('religion_name' => 'name'),'left')                    
                    ->where(array('newapplication.pkey = ?' => $pkey));        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;       
    } 
    public function getApplicantPDF($pkey)
    {
        $pkey  = (int) $pkey;
        $select = $this->tableGateway->getSql()->select()
                    ->join(array('i'=>'instructortypelov'), 'i.pkey = newapplication.InstructorTypeLOV_pkey',array('instructor_type_name' => 'name'))
                    ->join(array('j'=>'joblisting'), 'j.pkey = newapplication.JobListing_pkey',array('job_name' => 'name'))
                    ->join(array('pa'=>'partners'), 'pa.pkey = j.Partners_pkey',array('partners_name'=>'name','partners_key' => 'pkey', 'domainName'))
                    ->join(array('s'=>'school'), 's.pkey = j.School_pkey',array('school_name' => 'name'))
                    ->join(array('e' => 'educationlistenum'), 'e.pkey = newapplication.EducationListEnum_pkey',array('highest_degree' => 'name'),'left')
                    ->join(array('r'=>'religionlov'), 'r.pkey = newapplication.ReligionLOV_pkey',array('religion_name' => 'name'),'left')                    
                    ->join(array('h'=>'hereaboutusenum'), 'h.pkey = newapplication.HereAboutUsEnum_pkey',array('here_about_us' => 'name'),'left')                    
                    ->join(array('sa'=>'statelistlov'), 'sa.pkey = newapplication.StateListLov_pkey',array('state_name' => 'name'),'left')                    
                    ->join(array('c'=>'countrylov'), 'c.pkey = newapplication.CountryLOV_pkey',array('country_name' => 'name'),'left')                    
                    ->join(array('cs'=>'statelistlov'), 'cs.pkey = newapplication.CaStateListLov_pkey',array('cstate_name' => 'name'),'left')                    
                    ->join(array('ps'=>'statelistlov'), 'ps.pkey = newapplication.PaStateListLov_pkey',array('pstate_name' => 'name'),'left')
                    ->join(array('d'=>'degreelistenum'), 'd.pkey = newapplication.DegreeListEnum_pkey',array('degree_name' => 'name'),'left')
                    ->join(array('m'=>'majorlistenum'), 'm.pkey = newapplication.MajorListEnum_pkey',array('major_name' => 'name'),'left')
                    ->join(array('pt'=>'phone_type'), 'pt.pkey = newapplication.S_AppPhoneType1',array('PhoneType1' => 'name'),'left')
                    ->join(array('pt2'=>'phone_type'), 'pt2.pkey = newapplication.S_AppPhoneType2',array('PhoneType2' => 'name'),'left')
                    ->where(array('newapplication.pkey = ?' => $pkey));        
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }        

    public function getApplicantByID($pkey)
    {
        $rowset = $this->tableGateway->select(array('pkey' => $pkey));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $pkey");
        }	
        return $row;
    }        

    public function saveApplicant(Applicants $applicant)
    {   
        $pkey = (int) $applicant->pkey;
        if($pkey>0)
        {    
            $data = array(
                'firstname' =>  $applicant->firstname,
                'lastname'  =>  $applicant->lastname,
                'homePhone'  =>  $applicant->homePhone,
                'workPhone'  =>  $applicant->workPhone,
                'cellPhone'  =>  $applicant->cellPhone,
                'presentSalary'  =>  $applicant->presentSalary,
                'address'  =>  $applicant->address,
                'address2'  =>  $applicant->address2,
                'city'  =>  $applicant->city,
                'CountryLOV_pkey'  =>  $applicant->CountryLOV_pkey,
                'StateListLov_pkey'  =>  $applicant->StateListLov_pkey,
                'zip'  =>  $applicant->zip,
                'isUSAuthorizedWorker'  =>  $applicant->isUSAuthorizedWorker,
                'isFelonyConvicted'  =>  $applicant->isFelonyConvicted,
                'felonyIfYes'  =>  $applicant->felonyIfYes,
                'EducationListEnum_pkey'  =>  $applicant->EducationListEnum_pkey,
                'totalExp'  =>  $applicant->totalExp,
                'onlinePostsecondaryTeachingExp'  =>  $applicant->onlinePostsecondaryTeachingExp,                
            );
            if ($this->getApplicantByID($pkey)) {				 
                $res = $this->tableGateway->update($data, array('pkey' => $pkey));
                return $res;
            } else {
                throw new \Exception('Applicant id does not exist');
            }
        }        
    }   
    public function saveWilkesApplicant(Applicants $applicant,$post)
    {        
        $pkey = (int) $applicant->pkey;        
        if($pkey>0)
        {    
            $data = array(  
                'HereAboutUsEnum_pkey' => $applicant->HereAboutUsEnum_pkey,
                'presentSalary' => $applicant->presentSalary,            
                'availableDate' => date('Y-m-d',  strtotime($applicant->availableDate)),
                'firstname' => $applicant->firstname,
                'middleName' => $applicant->middleName,
                'lastname' => $applicant->lastname,
                'telephoneNo' => $applicant->telephoneNo,            
                'castreet' => $applicant->castreet,
                'cabox' => $applicant->cabox,
                'cacity' => $applicant->cacity,
                'CaStateListLov_pkey' => $applicant->CaStateListLov_pkey,
                'cazip' => $applicant->cazip,
                'pastreet' => $applicant->pastreet,
                'pabox' => $applicant->pabox,
                'pacity' => $applicant->pacity,
                'PaStateListLov_pkey' => $applicant->PaStateListLov_pkey,
                'pazip' => $applicant->pazip,
                'otherNames' => $applicant->otherNames,
                'ifagedovereighteen' => $applicant->ifagedovereighteen,
                'isUSAuthorizedWorker' => $applicant->isUSAuthorizedWorker,
                'isCityofPhiladelphia' => $applicant->isCityofPhiladelphia,
                'isFelonyConvicted' => $applicant->isFelonyConvicted,
                'felonyIfYes' => $applicant->felonyIfYes,
                'hignSchool' => $applicant->hignSchool,
                'highSchoolAddress' => $applicant->highSchoolAddress,
                'highSchoolTelephoneNo' => $applicant->highSchoolTelephoneNo,
                'ishighSchoolGraduate' => $applicant->ishighSchoolGraduate,
                'ifnoHighSchoolGraduate' => $applicant->ifnoHighSchoolGraduate,
                'gedObtained' => $applicant->gedObtained,    
                'college1' => $applicant->college1,
                'collegeAddress1' => $applicant->collegeAddress1,
                'collegeTelephoneNo1' => $applicant->collegeTelephoneNo1,
                'iscollegeGraduate' => $applicant->iscollegeGraduate,
                'ifnocollegeGraduate' => $applicant->ifnocollegeGraduate,
                'gradePointAvg' => $applicant->gradePointAvg,
                'DegreeListEnum_pkey' => $applicant->DegreeListEnum_pkey,
                'degreeOther' => $applicant->degreeOther,
                'MajorListEnum_pkey' => $applicant->MajorListEnum_pkey,
                'majorOther' => $applicant->majorOther,
                'minor' => $applicant->minor,
                'otherEducation' => $applicant->otherEducation,
                'certification' => $applicant->certification,
                'awards' => $applicant->awards,
                'foreignLanguage' => $applicant->foreignLanguage,
                'militaryApplicability' => $applicant->militaryApplicability,
                'militaryservicefrom' => (!empty($applicant->militaryservicefrom)) ? date('Y-m-d',strtotime($applicant->militaryservicefrom)) : NULL,
                'militaryserviceto' => (!empty($applicant->militaryserviceto)) ? date('Y-m-d',strtotime($applicant->militaryserviceto)) : NULL,
                'militaryBranch' => $applicant->militaryBranch,
                'militaryrank' => $applicant->militaryrank,
                'militaryExp' => $applicant->militaryExp,
                'skillsAndExp' => $applicant->skillsAndExp,
                'isPreviouslyEmployed' => $applicant->isPreviouslyEmployed,
                'ifyesFromEmp' => (!empty($applicant->ifyesFromEmp)) ? date('Y-m-d',strtotime($applicant->ifyesFromEmp)) : NULL,
                'ifyesToEmp' => (!empty($applicant->ifyesToEmp)) ? date('Y-m-d',strtotime($applicant->ifyesToEmp)) : NULL,
                'presentSalaryEmp' => $applicant->presentSalaryEmp,
                'salaryExpectedEmp' => $applicant->salaryExpectedEmp,
                'hoursAvailableEmp' => $applicant->hoursAvailableEmp,
                'regularFullTime' => $applicant->regularFullTime,
                'regularHalfTime' => $applicant->regularHalfTime,
                'temporary' => $applicant->temporary,
                'seasonal' => $applicant->seasonal,
                'days' => $applicant->days,
                'night' => $applicant->night,
                'weekends' => $applicant->weekends,
                'holidays' => $applicant->holidays,
                'relativeNameList' => $applicant->relativeNameList,
                'emergencyContactName' => $applicant->emergencyContactName,
                'emergencyContactNo' => $applicant->emergencyContactNo,
                'canYouTravel' => $applicant->canYouTravel,
                'mayContactEmp' => $applicant->mayContactEmp,
                'specialTrainingandInterest' => $applicant->specialTrainingandInterest,
                'listAffiliations' => $applicant->listAffiliations,
                'isContractualObligations' => $applicant->isContractualObligations,
                'ifyesContractualObligations' => $applicant->ifyesContractualObligations,
                'referenceName' => $applicant->referenceName,
                'referenceAddress' => $applicant->referenceAddress,
                'referencePhoneNumber' => $applicant->referencePhoneNumber,
                'referenceOccupation' => $applicant->referenceOccupation,
                'referenceName2' => $applicant->referenceName2,
                'referenceAddress2' => $applicant->referenceAddress2,
                'referencePhoneNumber2' => $applicant->referencePhoneNumber2,
                'referenceOccupation2' => $applicant->referenceOccupation2,
                'referenceName3' => $applicant->referenceName3,
                'referenceAddress3' => $applicant->referenceAddress3,
                'referencePhoneNumber3' => $applicant->referencePhoneNumber3,
                'referenceOccupation3' => $applicant->referenceOccupation3,
                'signature' => $applicant->signature,                
            );            
            if ($this->getApplicantByID($pkey)) {	                                                
                $res = $this->tableGateway->update($data, array('pkey' => $pkey));
                //Update wilkesemployerinfo   
                $this->updateWilkesEmployerInfo($post,$pkey);
                return $res; 
            } else {
                return false;
            }
        }        
    }        
    public function saveAdditionalApplicant($applicant)
    {
        $pkey = (int) $applicant->pkey;        
        if($pkey>0)
        {
            $data = array(
                    'additional_university' => $applicant->additional_university,
                    'additional_profit_nonprofit' => $applicant->additional_profit_nonprofit,
                    'additional_highest_degree' => $applicant->additional_highest_degree,
                    'additional_payrate' => $applicant->additional_payrate,
                    'additional_university_email' => $applicant->additional_university_email,
                    'additional_education_exp' => $applicant->additional_education_exp,
                    'additional_online_exp' => $applicant->additional_online_exp,
                    'additional_start_date' => (!empty($applicant->additional_start_date)) ? date('Y-m-d',strtotime($applicant->additional_start_date)) : NULL,
                    'additional_job_type' => $applicant->additional_job_type,                    
                    );
            if ($this->getApplicantByID($pkey)) {	                                                
                $res = $this->tableGateway->update($data, array('pkey' => $pkey));                
                return $res; 
            } else {
                return false;
            }
        }
    }  
    public function saveNotesApplicant($applicant)
    {
        $pkey = (int) $applicant->pkey;        
        if($pkey>0)
        {
            $data = array(                   
                    'notes' => $applicant->notes,
                    );
            if ($this->getApplicantByID($pkey)) {	                                                
                $res = $this->tableGateway->update($data, array('pkey' => $pkey));                
                return $res; 
            } else {
                return false;
            }
        }
    }
        
    public function updateWilkesEmployerInfo($post,$pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();
        if($post->wilkesemployer_count>0)
        {
            for($i=1; $i<=$post->wilkesemployer_count; $i++)
            {
                $emply_pkey = 'wilkesemployerinfo_pkey_'.$i;
                $emply_name = 'name_'.$i;
                $emply_address = 'address_'.$i;
                $emply_telNo = 'telNo_'.$i;
                $emply_business = 'business_'.$i;
                $emply_job = 'job_'.$i;
                $emply_salary = 'salary_'.$i;
                $emply_supervisor = 'supervisor_'.$i;
                $emply_currentEmployer = 'currentEmployer_'.$i;
                $emply_reasonForLeaving = 'reasonForLeaving_'.$i;
                $emply_empDateFrom = 'empDateFrom_'.$i;
                $emply_empDateTo = 'empDateTo_'.$i;                        
                $emp_pkey = $post->$emply_pkey;
                $employerinfo = array();
                $employerinfo[] = 'name = "'.$post->$emply_name.'" ';
                $employerinfo[] = 'address = "'.$post->$emply_address.'" ';
                $employerinfo[] = 'telNo = "'.$post->$emply_telNo.'" ';
                $employerinfo[] = 'business = "'.$post->$emply_business.'" ';
                $employerinfo[] = 'job = "'.$post->$emply_job.'" ';
                $employerinfo[] = 'salary = "'.$post->$emply_salary.'" ';
                $employerinfo[] = 'supervisor = "'.$post->$emply_supervisor.'" ';
                $employerinfo[] = 'currentEmployer = "'.$post->$emply_currentEmployer.'" ';
                $employerinfo[] = 'reasonForLeaving = "'.$post->$emply_reasonForLeaving.'" ';
                $employerinfo[] = 'empDateFrom = "'.date('Y-m-d',  strtotime($post->$emply_empDateFrom)).'" ';
                $employerinfo[] = 'empDateTo = "'.date('Y-m-d',  strtotime($post->$emply_empDateTo)).'" ';
                $implodeArray = implode(', ', $employerinfo);                        
                $sql = "UPDATE wilkesemployerinfo SET $implodeArray WHERE pkey=".$emp_pkey. " AND NewApplicationInfo_pkey=".$pkey;                
                $statement = $dbAdapter->query($sql);
                $result = $statement->execute();
            }
        }
        return true;
    }        

    public function getDocumentsByApplicant($pkey) 
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('pkey','docName','submittedOn','docFilePath','time'));
        $select->from(array('d' => 'documentsmetadata'));      
        $select->join(array('dt' => 'doctypelov'), 'dt.pkey = d.DocTypeLov_pkey',array('doc_category'=>'category'));
        $select->where->equalTo('d.References_pkey', $pkey);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rows = array();
        if(count($resultSet)>0)
        {   
            foreach ($resultSet as $res)
            {
                $rows[$res->doc_category][] = $res;
            }    
        }
        return $rows;
    }
    public function getcoursesList($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();        
        $coursesQualified = $this->getcoursesQualifiedByApplicant($pkey);        
        $qualified_list = array();
        if(count($coursesQualified)>0) 
        {
            foreach ($coursesQualified as $course)
            {
                $qualified_list[] = $course->pkey;
            }    
        }                  
        $select->columns(array('pkey','code','name'));
        $select->from(array('cd' => 'coursedef'));
        $select->join(array('cd1' => 'coursedef'), 'cd1.pkey = cd.parentReference_pkey',array('group_name'=>'name'));
        $select->join(array('csd' => 'coursesdef'), 'csd.code = cd.code AND csd.Partners_pkey = cd.Partners_pkey', array(),'left');
        $select->join(array('d' => 'degreeprogramlevellist'), 'd.pkey = csd.DegreeProgramLevelList_pkey',array('group_code'=>'code','group_name'=>'name'),'left');
        $select->join(array('pa' => 'partners'), 'pa.pkey = cd.Partners_pkey', array());
        $select->join(array('job' => 'joblisting'), 'job.Partners_pkey = pa.pkey', array());
        $select->join(array('c' => 'newapplication'), 'c.JobListing_pkey = job.pkey', array());
        $select->where->equalTo('c.pkey', $pkey);
        if(count($qualified_list)>0)
        {
            $courses_ids = implode(',',$qualified_list);
            $select->where('cd.pkey NOT IN ('.$courses_ids.')');
        }    
        $select->where('cd.parentReference_pkey IS NOT NULL');
        $select->group('cd.pkey');
        $select->order('cd.code');
                
        /*$select->columns(array('pkey','code','name'));
        $select->from(array('cd' => 'coursesdef'));  
        $select->join(array('d' => 'degreeprogramlevellist'), 'd.pkey = cd.DegreeProgramLevelList_pkey',array('group_code'=>'code','group_name'=>'name'));
        $select->join(array('pa' => 'partners'), 'pa.pkey = cd.Partners_pkey', array());
        $select->join(array('job' => 'joblisting'), 'job.Partners_pkey = pa.pkey', array());
        $select->join(array('c' => 'newapplication'), 'c.JobListing_pkey = job.pkey', array());
        $select->where->equalTo('c.pkey', $pkey); 
        if(count($qualified_list)>0)
        {
            $courses_ids = implode(',',$qualified_list);
            $select->where('cd.pkey NOT IN ('.$courses_ids.')');
        }  
        $select->order('cd.code');*/
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;  
    }        
    public function getcoursesQualifiedByApplicant($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        /*$select->columns(array('pkey','code','name','description'));
        $select->from(array('cd' => 'coursesdef'));  
        $select->join(array('d' => 'degreeprogramlevellist'), 'd.pkey = cd.DegreeProgramLevelList_pkey',array('group_code'=>'code','group_name'=>'name'));
        $select->join(array('pc' => 'partyinfohascourses'), 'pc.CourseDef_pkey = cd.pkey',array('partyinfo_id'=>'pkey'));        
        $select->join(array('c' => 'newapplication'), 'c.PartyInfo_pkey = pc.PartyInfo_pkey', array());
        $select->join(array('job' => 'joblisting'), 'job.pkey = c.JobListing_pkey', array());
        $select->join(array('pa' => 'partners'), 'pa.pkey = job.Partners_pkey AND pa.pkey = pc.Partners_pkey ', array());
        $select->join(array('ca' => 'candidateapproved'), 'ca.PartyInfoHasCourses_pkey = pc.pkey',array('is_approved'=>'is_approved'),'left');
        $select->where->equalTo('c.pkey', $pkey);                
        $select->order("pc.pkey ASC");
        */
        $select->columns(array('pkey','code','name','description'));
        $select->from(array('cd' => 'coursedef'));  
        $select->join(array('cd1' => 'coursedef'), 'cd1.pkey = cd.parentReference_pkey',array('group_name'=>'name'));
        $select->join(array('csd' => 'coursesdef'), 'csd.code = cd.code AND csd.Partners_pkey = cd.Partners_pkey', array(),'left');
        $select->join(array('d' => 'degreeprogramlevellist'), 'd.pkey = csd.DegreeProgramLevelList_pkey',array('group_code'=>'code','group_name'=>'name'),'left');
        $select->join(array('pc' => 'partyinfohascourses'), 'pc.CourseDef_pkey = cd.pkey',array('partyinfo_id'=>'pkey'));        
        $select->join(array('c' => 'newapplication'), 'c.PartyInfo_pkey = pc.PartyInfo_pkey', array());
        $select->join(array('job' => 'joblisting'), 'job.pkey = c.JobListing_pkey', array());
        $select->join(array('pa' => 'partners'), 'pa.pkey = job.Partners_pkey AND pa.pkey = pc.Partners_pkey ', array());
        $select->join(array('ca' => 'candidateapproved'), 'ca.PartyInfoHasCourses_pkey = pc.pkey',array('is_approved'=>'is_approved'),'left');
        $select->where->equalTo('c.pkey', $pkey);
        $select->where->equalTo('pc.newapplication_pkey', $pkey);        
        $select->group('pc.pkey');
        $select->order("pc.pkey ASC");      
        //echo $select->getSqlString();exit;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rows = array();
        foreach($resultSet as $res):
            $rows[] = $res;
        endforeach;        
        return $rows;        
    }        
    public function appStatusChangeHistory($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->from(array('s' => 'applicationchangehistory'));
        $select->join(array('a1' => 'appstatuslov'), 'a1.pkey = s.fromStatus_pkey', array('from_status'=>'name'));
        $select->join(array('a2' => 'appstatuslov'), 'a2.pkey = s.toStatus_pkey', array('to_status'=>'name'));
        $select->join(array('p' => 'partyinfo'), 'p.pkey = s.changedbyPartyInfo_pkey', array('firstName'=>'firstName','lastName'=>'lastName'),'left');
        $select->where->equalTo('s.newApplication_pkey', $pkey); 
        $select->order('s.dateOfChange DESC');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        $rows = array();
        if(count($resultSet)>0)
        {   
            foreach ($resultSet as $res)
            {
                $rows[] = $res;
            }    
        }
        return $rows;
    } 
    public function getWilkesemployerinfo($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->from(array('we' => 'wilkesemployerinfo'));        
        $select->where->equalTo('we.NewApplicationInfo_pkey', $pkey); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }
    
    public function getColumnvalue($pkey, $column_name)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array($column_name));
        $select->from(array('c' => 'newapplication'));        
        $select->where->equalTo('c.pkey', $pkey);         
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    } 
    public function getDocumentsById($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('pkey','DocTypeLov_pkey','References_pkey','docName','docFilePath'));
        $select->from(array('d' => 'documentsmetadata'));              
        $select->where->equalTo('d.pkey', $pkey);
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
    public function getApplicationStatusList()
    {
        $dbAdapter = $this->tableGateway->getAdapter();	              
        $sql = 'SELECT pkey,name FROM appstatuslov ORDER BY name ASC';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array(); 
        $selectData[''] = '' ;
        foreach ($result as $res) {
           $selectData[$res['pkey']] = $res['name'];
        }
        return $selectData; 
    }
    public function getPartyInfoKey($user_id)
    {
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = 'SELECT partyInfo_pkey FROM user_partyinfo_linker WHERE user_id='.$user_id;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);        
        $row = $resultSet->current();
        return $row->partyInfo_pkey ;        
    }        

    public function insertApplicationChangeHistory($post,$user_id)
    {
        //newapplication
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = 'UPDATE newapplication SET AppStatusLOV_pkey = '.$post->toStatus_pkey.',notes = "'.$post->Notes.'" WHERE pkey='.$post->newApplication_pkey;
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        
        $changedbyPartyInfo_pkey = $this->getPartyInfoKey($user_id);
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = 'INSERT INTO `applicationchangehistory` (fromStatus_pkey,toStatus_pkey,Notes,newApplication_pkey,partyInfo_pkey,changedbyPartyInfo_pkey,dateOfChange) VALUES ("'.$post->fromStatus_pkey.'","'.$post->toStatus_pkey.'","'.$post->Notes.'","'.$post->newApplication_pkey.'","'.$post->partyInfo_pkey.'","'.$changedbyPartyInfo_pkey.'","'.date('Y-m-d H:i:s').'")';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        return true;
    }

    public function updateApplicationStatus($newapplicationID,$newStatus){
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = "update `newapplication` set AppStatusLOV_pkey = $newStatus where pkey = $newapplicationID";
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
    }

    public function updateNewHireStatus($newapplicationID){
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = "update partyinfohasstatus as pihs
join newapplication as na
on (pihs.PartyInfo_pkey = na.PartyInfo_pkey)
set pihs.ApplicantStatusLov_pkey = 2
where na.pkey = $newapplicationID;";
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();

        //flag user as faculty (seen in java side)
        $sql = "update partyinfohasroles as pihr
join newapplication as na
on (pihr.PartyInfo_pkey = na.PartyInfo_pkey)
set pihr.PartyTypeEnum_pkey = 10
where na.pkey = $newapplicationID;";
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
    }


    public function insertApplicantFilter($applicant_filter_name,$user_id)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	              
        $lastId = '';              
        $PartyInfo_pkey = $this->getPartyInfoKey($user_id);
        $sql = 'INSERT INTO `applicantfilter` (name,PartyInfo_pkey,savedOn) VALUES ("'.$applicant_filter_name.'","'.$PartyInfo_pkey.'","'.time().'")';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $lastId = $dbAdapter->getDriver()->getConnection()->getLastGeneratedValue();
        return $lastId;
    }  
    public function insertApplicantFilterTable($table,$applicant_filter_id,$insert_in,$post)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        foreach ($post as $value)
        {    
            $sql = 'INSERT INTO '.$table.' (ApplicantFilter_pkey,'.$insert_in.') VALUES ("'.$applicant_filter_id.'","'.$value.'")';
            $statement = $dbAdapter->query($sql);
            $result = $statement->execute();
        }
        return true;    
    }  
    public function insertApplicantFilterDate($applicant_filter_id,$from_date,$to_date)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $startDate = date('Y-m-d',  strtotime($from_date));
        $endDate = date('Y-m-d',  strtotime($to_date));
        $sql = 'INSERT INTO applicantfilterdate (ApplicantFilter_pkey,startDate,endDate) VALUES ("'.$applicant_filter_id.'","'.$startDate.'","'.$endDate.'")';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        return true;
    }   
    public function getApplicantFilterTable($load_filter_id,$table,$column_name)
    {
        $dbAdapter = $this->tableGateway->getAdapter();         
        $sql = 'SELECT '.$column_name.' FROM '.$table. ' WHERE ApplicantFilter_pkey='.(int) $load_filter_id;        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();         
        foreach ($result as $res) {
           $selectData[] = $res[$column_name];
        }             
        return $selectData;
    }    
    public function getApplicantFilterTableDate($load_filter_id)
    {
        $dbAdapter = $this->tableGateway->getAdapter();         
        $sql = 'SELECT startDate,endDate  FROM applicantfilterdate WHERE ApplicantFilter_pkey='.(int) $load_filter_id;        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        $selectData = array();         
        foreach ($result as $res) {
           $selectData['startDate'] = $res['startDate'];
           $selectData['endDate'] = $res['endDate'];
        }             
        return $selectData;
    }
    public function getFilterTitle($table,$pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('name'));
        $select->from(array('t' => $table));              
        $select->where->equalTo('t.pkey', $pkey);        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }
    public function getFilterTitleCourse($table,$pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('code'));
        $select->from(array('t' => $table));      
        $select->join(array('t1' => $table), 't1.pkey = t.parentReference_pkey', array('group_code'=>'code'), ''); 
        $select->where->equalTo('t.pkey', $pkey);        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }
    public function deleteApplicantFilter($post,$table_array)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $ids = implode(',',$post);        
        if(count($table_array)>0)
        {
            foreach($table_array as $table)
            {    
                $sql = 'DELETE FROM '.$table.' WHERE ApplicantFilter_pkey IN ('.$ids.')';
                $statement = $dbAdapter->query($sql);
                $result = $statement->execute();  
            }    
        }
        $sql = 'DELETE FROM applicantfilter WHERE pkey IN ('.$ids.')';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();  
        return true;    
    } 
    public function insertPartyInfoCourses($post)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        if(count($post->courses_add_check)>0)
        {    
            foreach ($post->courses_add_check as $value)
            {    
                $sql = 'INSERT INTO partyinfohascourses (PartyInfo_pkey,CourseDef_pkey,Partners_pkey,newapplication_pkey) VALUES ("'.$post->PartyInfo_pkey.'","'.$value.'","'.$post->Partners_pkey.'","'.$post->Newapplication_pkey.'")';
                $statement = $dbAdapter->query($sql);
                $result = $statement->execute();
            }
        }    
        return true;
    }  
    public function deletePartyInfoCourses($partyinfo_id_array)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $partyinfo_ids = implode(",",$partyinfo_id_array);
        $sql = 'DELETE FROM partyinfohascourses WHERE pkey IN ('.$partyinfo_ids.')';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute(); 
        return true;
    }
    public function insertDocumentmetadata($data)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $sql = new Sql($dbAdapter);
        $insert = $sql->insert('documentsmetadata');         
        $insert->values($data);
        $selectString = $sql->getSqlStringForSqlObject($insert);        
        $statement = $dbAdapter->query($selectString);
        $result = $statement->execute();
        $doc_pkey = $this->tableGateway->adapter->getDriver()->getLastGeneratedValue();
        return $doc_pkey;
    }    
    public function deleteDocumentmetadata($docs_id_array)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $docs_ids = implode(",",$docs_id_array);
        $sql = 'DELETE FROM licensestypeapp WHERE DocumentsMetaData_pkey IN ('.$docs_ids.')';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute(); 
        $sql = 'DELETE FROM documentsmetadata WHERE pkey IN ('.$docs_ids.')';        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute(); 
        return true;
    }   
    public function insertAuditApplicantHistory($data)
    {
        $dbAdapter = $this->tableGateway->getAdapter();	
        $sql = new Sql($dbAdapter);
        $insert = $sql->insert('audittran');         
        $insert->values($data);
        $selectString = $sql->getSqlStringForSqlObject($insert);        
        $statement = $dbAdapter->query($selectString);
        $result = $statement->execute();
        return true;
    }
    public function getApplicationAuditHistory($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        $select->columns(array('role','user','date','time','businessEventStatus','fromValue','toValue'));
        $select->from(array('a' => 'audittran'));          
        $select->join(array('b' => 'businesseventypeenum'), 'b.pkey = a.BusinessEventTypeEnum_pkey', array('business_event'=>'name'));
        $select->where->equalTo('a.Newapplication_pkey', $pkey);
        $select->order('a.pkey DESC');
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet; 
    }     
    public function saveCandidateApproved($post)
    {
        $party_course_ids = json_decode($post->party_course_ids);
        $Newapplication_pkey = $post->Newapplication_pkey;
        $dbAdapter = $this->tableGateway->getAdapter();	
        //Delete before inserting candidate approved...
        $sql = 'DELETE FROM candidateapproved WHERE Newapplication_pkey='.$Newapplication_pkey;        
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute(); 
        //Inserting candidate approved...
        if(count($party_course_ids)>0)
        {
            foreach($party_course_ids as $party_course)
            {
                $insert = 'INSERT INTO candidateapproved (PartyInfoHasCourses_pkey,Newapplication_pkey,is_approved) VALUES ("'.$party_course.'","'.$Newapplication_pkey.'","1")';
                $statement = $dbAdapter->query($insert);
                $result = $statement->execute();
            }    
        }    
        return true;
    }      
    public function getCurrentStatus($pkey)
    {
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();   
        $select->columns(array('AppStatusLOV_pkey'));
        $select->from(array('n' => 'newapplication'));
        $select->join(array('a' => 'appstatuslov'), 'a.pkey = n.AppStatusLOV_pkey', array('status'=>'name'));
        $select->where->equalTo('n.pkey', $pkey);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);  
        $row = $resultSet->current();
        $res = array();
        $res['AppStatusLOV_pkey'] = $row->AppStatusLOV_pkey;
        $res['current_status'] = $row->status;        
        return $res; 
    }
    public function saveAdlerApplicant($applicants)
    {
        $data = array();
        foreach($applicants as $key => $value){
            $data[$key] = $value;
        }         
        $id = (int) $data['pkey'];
        if($id >0){
            unset($data['pkey']);
            unset($data['DataTables_Table_0_length']);   
            unset($data['is_approved']);
            unset($data['submit']);
            unset($data['DataTables_Table_1_length']);
            unset($data['DataTables_Table_2_length']);
            if ($this->getApplicantByID($id)) {				 
                $this->tableGateway->update($data, array('pkey' => $id));
                return $id;
            } else {
                throw new \Exception('Applicant id does not exist');
            }
        }
    }      
    public function getLicenseTypeList(){
        $row = array();
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();        
        $select->from(array('l' => 'licensestypelov'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        if(count($resultSet)>0){
            foreach ($resultSet as $res){
                $row[$res->pkey] = $res->name;
            }            
        }
        return $row;
    }
    public function getIssuingAgencyTypeList(){
        $row = array();
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();        
        $select->from(array('i' => 'issuingagencylov'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        if(count($resultSet)>0){
            foreach ($resultSet as $res){
                $row[$res->pkey] = $res->name;
            }            
        }
        return $row;
    }
    public function getCertificateTypeList(){
        $row = array();
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();        
        $select->from(array('c' => 'certificationtypelov'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        if(count($resultSet)>0){
            foreach ($resultSet as $res){
                $row[$res->pkey] = $res->name;
            }            
        }
        return $row;
    }
    public function insertLicensestypeapp($data)
    {
        if($data['expirationDate']==''){
            $data['expirationDate'] = NULL;
        }
        $dbAdapter = $this->tableGateway->getAdapter();	
        $sql = new Sql($dbAdapter);
        $insert = $sql->insert('licensestypeapp');         
        $insert->values($data);
        $selectString = $sql->getSqlStringForSqlObject($insert);        
        $statement = $dbAdapter->query($selectString);
        $result = $statement->execute();
        $doc_pkey = $this->tableGateway->adapter->getDriver()->getLastGeneratedValue();
        return $doc_pkey;
    } 
    public function updateLicensestypeapp($data, $pkey){
        if($data['expirationDate']==''){
            $data['expirationDate'] = NULL;
        }
        if($data['LicensesTypeLOV_pkey']!='4'){
            $data['otherlicensestype'] = NULL;
        }
        if($data['IssuingAgencyLOV_pkey']!='3'){
            $data['otherissuingagency'] = NULL;
        }
        if($data['CertificationTypeLOV_pkey']!='5'){
            $data['othercertificationtype'] = NULL;
        }
        $dbAdapter = $this->tableGateway->getAdapter();	
        $sql = new Sql($dbAdapter);
        $update = $sql->update('licensestypeapp');                 
        $update->set($data);
        $update->where("pkey = ".$pkey);        
        $selectString = $sql->getSqlStringForSqlObject($update);        
        $statement = $dbAdapter->query($selectString);
        $statement->execute();
        return true;
    }
    public function getLicensestypeapp($pkey){
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->from(array('l' => 'licensestypeapp')); 
        $select->join(array('li' => 'licensestypelov'), 'li.pkey = l.LicensesTypeLOV_pkey', array('license_type'=>'name'),'left');
        $select->join(array('i' => 'issuingagencylov'), 'i.pkey = l.IssuingAgencyLOV_pkey', array('issue_type'=>'name'),'left');
        $select->join(array('c' => 'certificationtypelov'), 'c.pkey = l.CertificationTypeLOV_pkey', array('certificate_type'=>'name'),'left');
        $select->where->equalTo('l.NewApplication_pkey', $pkey);
        //echo $select->getSqlString();exit;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $rows = array();
        foreach($resultSet as $res){
            $rows[] = $res;
        }
        return $rows;
    }
    public function getDocumentUpload($pkey){
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('pkey','filename'));
        $select->from(array('l' => 'licensestypeapp'));              
        $select->where->equalTo('l.pkey', $pkey);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        return $row->filename;
    }
    public function getAdlerDegreeType($pkey){
       $data = array();
       $dbAdapter = $this->tableGateway->getAdapter();                
       $sql = new Sql($dbAdapter);
       $select = $sql->select();      
       $select->columns(array());
       $select->from(array('n' => 'newapplication'));
       $select->join(array('el'=>'educationlistenum'), 'el.pkey = n.S_DegreeDoc',array('degree_doc_type' => 'name'),'left');
       $select->join(array('el2'=>'educationlistenum'), 'el2.pkey = n.S_DegreeMast',array('degree_mast_type' => 'name'),'left');
       $select->join(array('el3'=>'educationlistenum'), 'el3.pkey = n.S_DegreeAssoc',array('degree_assoc_type' => 'name'),'left');
       $select->join(array('el4'=>'educationlistenum'), 'el4.pkey = n.S_DegreeBach',array('degree_bach_type' => 'name'),'left');
       $select->where->equalTo('n.pkey', $pkey);       
       $statement = $sql->prepareStatementForSqlObject($select);        
       $result = $statement->execute();
       $resultSet = new ResultSet();
       $resultSet->initialize($result);
       $row = $resultSet->current();  
       return $row;
    }
    
    public function getJobDetailByPkey($pkey){
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        $select->columns(array('pkey','job_name' => 'name'));
        $select->from(array('j' => 'joblisting')); 
        $select->join(array('p' => 'partners'), 'p.pkey = j.Partners_pkey',array('partner' => 'name','domainName'),'');
        $select->join(array('n' => 'newapplication'), 'n.JobListing_pkey = j.pkey',array(),'');
        $select->where->equalTo('n.pkey', $pkey);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        return $row;
    }
    public function getNewapplicationInfo($pkey,$columns){
       $data = array();
       $dbAdapter = $this->tableGateway->getAdapter();                
       $sql = new Sql($dbAdapter);
       $select = $sql->select();      
       $select->columns(array_keys($columns));
       $select->from(array('n' => 'newapplication'));
       //$select->join(array('j'=>'joblisting'), 'j.pkey = n.JobListing_pkey',array('job_name' => 'name'));
       $select->join(array('c'=>'countrylov'), 'c.pkey = n.S_AppCountry',array('countryname' => 'name'),'left');
       $select->join(array('s'=>'statelistlov'), 's.pkey = n.S_AppState',array('statename' => 'name'),'left');
       $select->join(array('t'=>'teaching_schedule'), 't.pkey = n.S_PrefSched',array('prefered_schedule' => 'name'),'left');
       $select->join(array('tt'=>'teaching_time'), 'tt.pkey = n.S_PrefTime',array('teaching_time' => 'name'),'left');
       $select->join(array('pt'=>'phone_type'), 'pt.pkey = n.S_AppPhoneType1',array('phone_type' => 'name'),'left');
       $select->join(array('pt2'=>'phone_type'), 'pt2.pkey = n.S_AppPhoneType2',array('phone_type2' => 'name'),'left');
       $select->join(array('el'=>'educationlistenum'), 'el.pkey = n.S_DegreeDoc',array('educationlistenum' => 'name'),'left');
       $select->join(array('el2'=>'educationlistenum'), 'el2.pkey = n.S_DegreeMast',array('educationlistenum2' => 'name'),'left');
       $select->join(array('el3'=>'educationlistenum'), 'el3.pkey = n.S_DegreeAssoc',array('educationlistenum3' => 'name'),'left');
       $select->join(array('el4'=>'educationlistenum'), 'el4.pkey = n.S_DegreeBach',array('educationlistenum4' => 'name'),'left');
       $select->join(array('re'=>'religionlov'), 're.pkey = n.S_ReligionLOV_pkey',array('religionname' => 'name'),'left');
       $select->join(array('m'=>'methodology'), 'm.pkey = n.S_Methodology_pkey',array('methodologyname' => 'name'),'left');
       $select->join(array('te'=>'teaching_experience'), 'te.pkey = n.S_TeachingExp',array('teachingexperience' => 'name'),'left');
       
       $select->where->equalTo('n.pkey', $pkey);
       $statement = $sql->prepareStatementForSqlObject($select);        
       $result = $statement->execute();
       $resultSet = new ResultSet();
       $resultSet->initialize($result);
       $row = $resultSet->current();  
       
       
       if(count($resultSet)>0){
           $i=0;
           foreach($row as $key => $value){
               if($key == 'S_ReasonLeaving1') {
                   $value_array = array();
                   $value = json_decode($row['S_ReasonLeaving1']);
                   //replacing the index value (ie. 1) with a human-meaningful value (ie. Discharged) in the HTML output
                    for($i=0;$i<count($value);$i++){
                       $value_array[] =  $this->getLookupValue($value[$i],'reason_leaving','name');                    
                   } 
                   $display_value = json_encode($value_array);
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $display_value);
               } else if($key == 'S_MajorListEnum_pkey'){
                   $value_array = array();
                   $value = json_decode($row['S_MajorListEnum_pkey']);
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Adult Health Nursing) in the HTML output
                   for($i=0;$i<count($value);$i++){
                       $value_array[] =  $this->getLookupValue($value[$i],'majorlistenum','name');                    
                   } 
                   $display_value = json_encode($value_array);
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $display_value);
               } else if($key == 'S_DegreeListEnum_pkey') {
                   $value_array = array();
                   $value = json_decode($row['S_DegreeListEnum_pkey']);
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Bachelor of Arts) in the HTML output
                   for($i=0;$i<count($value);$i++){
                       $value_array[] =  $this->getLookupValue($value[$i],'degreelistenum','name');                    
                   } 
                   $display_value = json_encode($value_array);
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $display_value );
                   
               } else if($key == 'S_PaStateListLov_pkey') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. California) in the HTML output
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => 
                       $this->getLookupValue($row['S_PaStateListLov_pkey'],'statelistlov','name'));
               } else if($key == 'S_AppState') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. California) in the HTML output
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => 
                       $this->getLookupValue($row['S_AppState'],'statelistlov','name'));
               } else if($key == 'S_HereAboutUsEnum_pkey') {
                   //replacing the index value (ie. 6) with a human-meaningful value (ie. Other) in the HTML output
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => 
                       $this->getLookupValue($row['S_HereAboutUsEnum_pkey'], 'hereaboutusenum', 'name'));
               } else if($key=='S_AppCountry'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['countryname']);
               } 
               else if($key=='S_AppState'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['statename']);
               } 
               else if($key=='S_PrefSched'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['prefered_schedule']);
               }  
               else if($key=='S_PrefTime'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['teaching_time']);
               }
               else if($key=='S_AppPhoneType1'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['phone_type']);
               }
               else if($key=='S_AppPhoneType2'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['phone_type2']);
               }
               else if($key=='S_DegreeDoc'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['educationlistenum']);
               }
               else if($key=='S_DegreeMast'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['educationlistenum2']);
               }
               else if($key=='S_DegreeAssoc'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['educationlistenum3']);
               }
               else if($key=='S_DegreeBach'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['educationlistenum4']);
               }
               else if($key=='S_ReligionLOV_pkey'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['religionname']);
               }
               else if($key=='S_Methodology_pkey'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['methodologyname']);
               }
               else if($key=='S_TeachingExp'){
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $row['teachingexperience']);
               }
               
               else if($key=='S_AuthorizedUS'){
                   if($row['S_AuthorizedUS']=='0') { $Authorized = 'No'; } else { $Authorized = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $Authorized);
               }
               else if($key=='S_Doct'){
                   if($row['S_Doct']=='N') { $S_Doct = 'No'; } else { $S_Doct = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Doct);
               }
               else if($key=='S_Mast'){
                   if($row['S_Mast']=='N') { $S_Mast = 'No'; } else { $S_Mast = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Mast);
               }
               else if($key=='S_Bach'){
                   if($row['S_Bach']=='N') { $S_Bach = 'No'; } else { $S_Bach = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Bach);
               }
               else if($key=='S_Assoc'){
                   if($row['S_Assoc']=='N') { $S_Assoc = 'No'; } else { $S_Assoc = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Assoc);
               }
               else if($key=='S_CertifyApp'){
                   if($row['S_CertifyApp']=='N') { $S_CertifyApp = 'No'; } else { $S_CertifyApp = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_CertifyApp);
               }
               else if($key=='S_Gender'){
                   if($row['S_Gender']=='M') { $S_Gender = 'Male'; } else { $S_Gender = 'Female'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Gender);
               }
               else if($key=='S_Veteran'){
                   if($row['S_Veteran']=='0') { $S_Veteran = 'No'; } else { $S_Veteran = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Veteran);
               }
               else if($key=='S_Hispanic'){
                   if($row['S_Hispanic'] == '0' ) { $S_Hispanic = 'No'; } else if($row['S_Hispanic'] == '1' ){ $S_Hispanic = 'Yes'; } else { $S_Hispanic = 'None';}
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Hispanic);
               }
               else if($key=='S_AmerIndAlaskNativ'){
                   if($row['S_AmerIndAlaskNativ'] == '0' ) { $S_AmerIndAlaskNativ = 'No'; } else{ $S_AmerIndAlaskNativ = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_AmerIndAlaskNativ);
               }
               else if($key=='S_Asian'){
                   if($row['S_Asian'] == '0' ) { $S_Asian = 'No'; } else{ $S_Asian = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Asian);
               }
               else if($key=='S_BlackAfricAmer'){
                   if($row['S_BlackAfricAmer'] == '0' ) { $S_BlackAfricAmer = 'No'; } else{ $S_BlackAfricAmer = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_BlackAfricAmer);
               }
               else if($key=='S_NativHawaiiPacIsland'){
                   if($row['S_NativHawaiiPacIsland'] == '0' ) { $S_NativHawaiiPacIsland = 'No'; } else{ $S_NativHawaiiPacIsland = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_NativHawaiiPacIsland);
               }
               else if($key=='S_White'){
                   if($row['S_White'] == '0' ) { $S_White = 'No'; } else{ $S_White = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_White);
               }
               else if($key=='S_TwoMoreRace'){
                   if($row['S_TwoMoreRace'] == '0' ) { $S_TwoMoreRace = 'No'; } else{ $S_TwoMoreRace = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_TwoMoreRace);
               }
               else if($key=='S_NotDiscl'){
                   if($row['S_NotDiscl'] == '0' ) { $S_NotDiscl = 'No'; } else{ $S_NotDiscl = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_NotDiscl);
               }
               else if($key=='S_Ifagedovereighteen'){
                   if($row['S_Ifagedovereighteen']=='N') { $S_Ifagedovereighteen = 'No'; } elseif($row['S_Ifagedovereighteen']=='Y') { $S_Ifagedovereighteen = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Ifagedovereighteen);
               }
               else if($key=='S_IsCityofPhiladelphia'){
                   if($row['S_IsCityofPhiladelphia']=='N') { $S_IsCityofPhiladelphia = 'No'; } elseif($row['S_IsCityofPhiladelphia']=='Y') { $S_IsCityofPhiladelphia = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsCityofPhiladelphia);
               }
               else if($key=='S_IsFelonyConvicted'){
                   if($row['S_IsFelonyConvicted']=='N') { $S_IsFelonyConvicted = 'No'; } elseif($row['S_IsFelonyConvicted']=='Y') { $S_IsCityofPhiladelphia = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsFelonyConvicted);
               }
               else if($key=='S_IshighSchoolGraduate'){
                   if($row['S_IshighSchoolGraduate']=='N') { $S_IshighSchoolGraduate = 'No'; } elseif($row['S_IshighSchoolGraduate']=='Y') { $S_IshighSchoolGraduate = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IshighSchoolGraduate);
               }
               else if($key=='S_GedObtained'){
                   if($row['S_GedObtained']=='N') { $S_GedObtained = 'No'; } elseif($row['S_GedObtained']=='Y') { $S_GedObtained = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_GedObtained);
               }
               else if($key=='S_IscollegeGraduate[]'){
                   if($row['S_IscollegeGraduate[]']=='N') { $S_IscollegeGraduate = 'No'; } elseif($row['S_IscollegeGraduate[]']=='Y') { $S_IscollegeGraduate = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IscollegeGraduate);
               }
               else if($key=='S_MilitaryApplicability'){
                   if($row['S_MilitaryApplicability']=='A') { $S_MilitaryApplicability = 'Active'; } elseif($row['S_MilitaryApplicability']=='R') { $S_MilitaryApplicability = 'Retired'; } elseif($row['S_MilitaryApplicability']=='NA') { $S_MilitaryApplicability = 'Not Applicable'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_MilitaryApplicability);
               }
               else if($key=='S_RegularFullTime'){
                   if($row['S_RegularFullTime']=='N') { $S_RegularFullTime = 'No'; } elseif($row['S_RegularFullTime']=='Y') { $S_RegularFullTime = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_RegularFullTime);
               }
               else if($key=='S_RegularHalfTime'){
                   if($row['S_RegularHalfTime']=='N') { $S_RegularHalfTime = 'No'; } elseif($row['S_RegularHalfTime']=='Y') { $S_RegularHalfTime = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_RegularHalfTime);
               }
               else if($key=='S_Temporary'){
                   if($row['S_Temporary']=='N') { $S_Temporary = 'No'; } elseif($row['S_Temporary']=='Y') { $S_Temporary = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Temporary);
               }
               else if($key=='S_Seasonal'){
                   if($row['S_Seasonal']=='N') { $S_Seasonal = 'No'; } elseif($row['S_Seasonal']=='Y') { $S_Seasonal = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Seasonal);
               }
               else if($key=='S_Days'){
                   if($row['S_Days']=='N') { $S_Days = 'No'; } elseif($row['S_Days']=='Y') { $S_Days = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Days);
               }
               else if($key=='S_Night'){
                   if($row['S_Night']=='N') { $S_Night = 'No'; } elseif($row['S_Night']=='Y') { $S_Night = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Night);
               }
               else if($key=='S_Weekends'){
                   if($row['S_Weekends']=='N') { $S_Weekends = 'No'; } elseif($row['S_Weekends']=='Y') { $S_Weekends = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Weekends);
               }
               else if($key=='S_Holidays'){
                   if($row['S_Holidays']=='N') { $S_Holidays = 'No'; } elseif($row['S_Holidays']=='Y') { $S_Holidays = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Holidays);
               }
               else if($key=='isPreviouslyEmployed'){
                   if($row['isPreviouslyEmployed']=='N') { $isPreviouslyEmployed = 'No'; } elseif($row['isPreviouslyEmployed']=='Y') { $isPreviouslyEmployed = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $isPreviouslyEmployed);
               }
               else if($key=='S_CanYouTravel'){
                   if($row['S_CanYouTravel']=='N') { $S_CanYouTravel = 'No'; } elseif($row['S_CanYouTravel']=='Y') { $S_CanYouTravel = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_CanYouTravel);
               }
               else if($key=='S_CurrentEmployer1[]'){
                   if($row['S_CurrentEmployer1[]']=='N') { $S_CurrentEmployer1 = 'No'; } elseif($row['S_CurrentEmployer1[]']=='Y') { $S_CurrentEmployer1 = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_CurrentEmployer1);
               }
               else if($key=='S_MayContactEmp'){
                   if($row['S_MayContactEmp']=='N') { $S_MayContactEmp = 'No'; } elseif($row['S_MayContactEmp']=='Y') { $S_MayContactEmp = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_MayContactEmp);
               }
               else if($key=='S_IsContractualObligations'){
                   if($row['S_IsContractualObligations']=='N') { $S_IsContractualObligations = 'No'; } elseif($row['S_IsContractualObligations']=='Y') { $S_IsContractualObligations = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsContractualObligations);
               }
               
               else if($key=='S_IsCurrentEmployer'){
                   if($row['S_IsCurrentEmployer']=='N') { $S_IsCurrentEmployer = 'No'; } elseif($row['S_IsCurrentEmployer']=='Y') { $S_IsCurrentEmployer = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsCurrentEmployer);
               }
               else if($key=='S_IsEmpUniv'){
                   if($row['S_IsEmpUniv']=='N') { $S_IsEmpUniv = 'No'; } elseif($row['S_IsEmpUniv']=='Y') { $S_IsEmpUniv = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsEmpUniv);
               }
               else if($key=='S_IsRelativeEmployed'){
                   if($row['S_IsRelativeEmployed']=='N') { $S_IsRelativeEmployed = 'No'; } elseif($row['S_IsRelativeEmployed']=='Y') { $S_IsRelativeEmployed = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsRelativeEmployed);
               }
               else if($key=='S_IsCertificate'){
                   if($row['S_IsCertificate']=='N') { $S_IsCertificate = 'No'; } elseif($row['S_IsCertificate']=='Y') { $S_IsCertificate = 'Yes'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsCertificate);
               }
               else if($key=='S_Ethnicity'){
                   if($row['S_Ethnicity']=='H') { $S_Ethnicity = 'Hispanic/Latino'; } elseif($row['S_Ethnicity']=='N') { $S_Ethnicity = 'Non-Hispanic/Latino'; }
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_Ethnicity);
               }
               else if($key=='S_IsSpring'){
                   if($row['S_IsSpring'] == '0' ) { $S_IsSpring = 'No'; } else if($row['S_IsSpring'] == '1' ){ $S_IsSpring = 'Yes'; } else { $S_IsSpring = 'None';}
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsSpring);
               }
               else if($key=='S_IsSummer'){
                   if($row['S_IsSummer'] == '0' ) { $S_IsSummer = 'No'; } else if($row['S_IsSummer'] == '1' ){ $S_IsSummer = 'Yes'; } else { $S_IsSummer = 'None';}
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsSummer);
               }
               else if($key=='S_IsFall'){
                   if($row['S_IsFall'] == '0' ) { $S_IsFall = 'No'; } else if($row['S_IsFall'] == '1' ){ $S_IsFall = 'Yes'; } else { $S_IsFall = 'None';}
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsFall);
               }
               else if($key=='S_IsWinter'){
                   if($row['S_IsWinter'] == '0' ) { $S_IsWinter = 'No'; } else if($row['S_IsWinter'] == '1' ){ $S_IsWinter = 'Yes'; } else { $S_IsWinter = 'None';}
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $S_IsWinter);
               }
               else if(isset($columns[$key]) && $columns[$key]!='') {
                   $data[$key] = array('label' => $columns[$key], 'key' => $key, 'value' => $value);                   
               }    
               $i++;
           }
       }        
       return $data;
   }
   
   public function save($data){        
        $id = (int) $data['pkey'];
        //Update partyinfo if data is not availbale
        $partyinfo = $this->getPartyInfoDetails($data['PartyInfo_pkey'],$data);                 
        ////////
        if($id == 0 && count($data)>0){
            unset($data['pkey']);
            unset($data['save-form']);
            unset($data['submit-form']);
            
            //Set firstname, lastname and email
            $data['firstname'] = $data['S_AppFirstName'];
            $data['lastname'] = $data['S_AppLastName'];
            $data['email'] = $data['S_AppEmail'];
            $data['applicationName'] = $data['firstname'].' '.$data['lastname'];
            //$data['AppStatusLOV_pkey'] = 8; //Application Submitted Status
            $data['AppliedDate'] = date('Y-m-d H:i:s');
            if($data['email']==''){
                $data['email'] = $partyinfo['userId'];
                $data['S_AppEmail'] = $partyinfo['userId'];
            }
            $this->tableGateway->insert($data);
            $pkey = $this->tableGateway->getLastInsertValue();
            return $pkey;
        } else {
            
            unset($data['pkey']);
            unset($data['save-form']);
            unset($data['submit-form']);    
            $data['firstname'] = $data['S_AppFirstName'];
            $data['lastname'] = $data['S_AppLastName'];
            $data['email'] = $data['S_AppEmail'];
            $data['applicationName'] = $data['firstname'].' '.$data['lastname'];
            if($data['email']==''){
                $data['email'] = $partyinfo['userId'];
                $data['S_AppEmail'] = $partyinfo['userId'];
            }
            
            if ($this->getApplicantByID($id)) {                
                $this->tableGateway->update($data, array('pkey' => $id));
                return $id;
            } else {
                throw new \Exception('Applicant id does not exist');
            }            
        }            
    }
    public function getPartyInfoDetails($id,$data)
    {		
        $id  = (int) $id;     
        $adapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($adapter);
        $statement = $adapter->query($sql);
        $sqlObject = new Sql($adapter);
        $update = $sqlObject->update();
        $insert = $sqlObject->insert();

        $sql = 'SELECT * FROM partyinfo WHERE pkey = '.$id;
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        $row = $result->current();
        
        //echo '<pre>'; print_r($data); echo '</pre>'; exit;
         
        if($row['phonenum1'] == '' && $row['phonetype1'] == '' && isset($data['S_AppPhoneNum1']) && isset($data['S_AppPhoneType1']))
        {
            $data = array(
                'phonenum1' => $data['S_AppPhoneNum1'],
                'phonetype1' => $data['S_AppPhoneType1']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if($row['phonenum2'] == '' && $row['phonetype2'] == '' && isset($data['S_AppPhoneNum2']) 
                && isset($data['S_AppPhoneType2']))
        {
            $data = array(
                'phonenum2' => $data['S_AppPhoneNum2'],
                'phonetype2' => $data['S_AppPhoneType2']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if($row['address'] == '' && isset($data['S_AppAddress']))
        {
            $data = array(
                'address' => $data['S_AppAddress']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if($row['address2'] == '' && isset($data['S_AppAddress2']))
        {
            $data = array(
                'address2' => $data['S_AppAddress2']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if($row['city'] == '' && isset($data['S_AppCity']))
        {
            $data = array(
                'city' => $data['S_AppCity']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if($row['zip'] == '' && isset($data['S_AppZipCode']))
        {
            $data = array(
                'zip' => $data['S_AppZipCode']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if(($row['StateListLov_pkey'] == '' || $row['StateListLov_pkey'] == '0') 
                && isset($data['S_AppState']))
        {
            $data = array(
                'StateListLov_pkey' => $data['S_AppState']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        if(($row['CountryLOV_pkey'] == '' || $row['CountryLOV_pkey'] == '0') 
                && isset($data['S_AppCountry']))
        {
            $data = array(
                'CountryLOV_pkey' => $data['S_AppCountry']
            );
            $update->table('partyinfo');
            $update->set($data);
            $update->where(array('pkey' => $id));
            $statement = $sqlObject->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }
        return $row;
    }
    public function checkDependentSections($post,$university){
        $data = array();
        $column_name = str_replace('[]','',$post->column_name);
        $column_value = $post->column_value;
        $dbAdapter = $this->tableGateway->getAdapter();     
        $sql = new Sql($dbAdapter);     
        $select = $sql->select();
        $select->columns(array('pkey'));
        $select->from(array('f' => 'formfields')); 
        $select->join(array('u' => 'formfields_university'), 'u.formfields_pkey = f.pkey', array());
        $select->where->equalTo('u.university', $university);
        $select->where->equalTo('f.columns', $column_name);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        //echo $row->pkey;
        if(isset($row->pkey) && $row->pkey>0){
            $select2 = $sql->select();
            $select2->columns(array('pkey'));
            $select2->from(array('r' => 'formfields_radiocheck'));
            $select2->where->equalTo('r.rc_value', $column_value);
            $select2->where->equalTo('r.formfields_pkey', $row->pkey);
            $statement2 = $sql->prepareStatementForSqlObject($select2);
            $result2 = $statement2->execute();
            $resultSet2 = new ResultSet();
            $resultSet2->initialize($result2);
            $rediocheckedvalue = $resultSet2->current();
            if($rediocheckedvalue->pkey > 0)
            {
                $sql = new Sql($dbAdapter);     
                $select = $sql->select();
                $select->columns(array('dependent_field','dependent_column','pkey'));
                $select->from(array('f' => 'formfields_section'));
                $select->where('find_in_set ('.$rediocheckedvalue->pkey.',f.dependent_column)');
                $statement = $sql->prepareStatementForSqlObject($select);
                $result = $statement->execute();
                $resultSet = new ResultSet();
                $resultSet->initialize($result); 
                if(count($resultSet)>0){
                    foreach ($resultSet as $ressection){
                        $data['result']['sectionid'][]  = $ressection->pkey;
                        $data['result']['display'][]  = 'block';
                    }
                }
                else
                {
                    $sql = new Sql($dbAdapter);     
                    $select2 = $sql->select();
                    $select2->columns(array('dependent_field','dependent_column','pkey'));
                    $select2->from(array('f' => 'formfields_section'));
                    $select2->where->equalTo('f.dependent_field', $row->pkey);
                    $statement2 = $sql->prepareStatementForSqlObject($select2);
                    $result2 = $statement2->execute();
                    $resultSet2 = new ResultSet();
                    $resultSet2->initialize($result2);
                    foreach ($resultSet2 as $res2){
                        $data['result']['sectionid'][]  = $res2->pkey;
                        $data['result']['display'][]  = 'none';
                    }
                }
            }  
        }
        return $data;
    }
    public function checkDependentColumns($post,$university){
        $data = array();
        $column_name = str_replace('[]','',$post->column_name);
        $column_value = $post->column_value;
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();
        $select->columns(array('pkey'));
        $select->from(array('f' => 'formfields')); 
        $select->join(array('u' => 'formfields_university'), 'u.formfields_pkey = f.pkey', array());
        $select->where->equalTo('u.university', $university);
        $select->where->equalTo('f.columns', $column_name);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        if(isset($row->pkey) && $row->pkey>0){
            $select2 = $sql->select();
            $select2->columns(array('pkey'));
            $select2->from(array('r' => 'formfields_radiocheck'));
            $select2->where->equalTo('r.rc_value', $column_value);
            $select2->where->equalTo('r.formfields_pkey', $row->pkey);
            $statement2 = $sql->prepareStatementForSqlObject($select2);
            //echo $select2->getSqlString();
            $result2 = $statement2->execute();
            $resultSet2 = new ResultSet();
            $resultSet2->initialize($result2);
            $rediocheckedvalue = $resultSet2->current();
            //echo $rediocheckedvalue->pkey;
            if($rediocheckedvalue->pkey > 0)
            {
                $sql = new Sql($dbAdapter);		
                $select = $sql->select();
                $select->columns(array('columns', 'type','dependent_field','is_add_more'));
                $select->from(array('f' => 'formfields'));
                $select->where('find_in_set ('.$rediocheckedvalue->pkey.',f.dependent_column)');
                $statement = $sql->prepareStatementForSqlObject($select);
                //echo $select->getSqlString();
                $result = $statement->execute();
                $resultSet = new ResultSet();
                $resultSet->initialize($result);
                if(count($resultSet)>0){
                    foreach ($resultSet as $res){
                        if($res->is_add_more=='yes'){
                            $data['result']['columns'][] = $res->columns.'[]';
                        } else {
                            $data['result']['columns'][] = $res->columns;
                        }    
                        $data['result']['type'][] = $res->type;
                        $parent_view = $this->getParentView($res->dependent_field);
                        if($parent_view == '0')
                        {
                            if($university == '3')
                            {
                                $data['result']['display'][]  = 'none';
                            }
                            else
                            {
                                $data['result']['display'][]  = 'none';
                            }
                        }
                        else
                        {
                            if($university == '3')
                            {
                                $data['result']['display'][]  = 'block';
                            }
                            else
                            {
                                $data['result']['display'][]  = 'block';
                            }
                        }
                        $data['result']['is_required'][]  = 'true';
                    }
                    $select3 = $sql->select();
                    $select3->columns(array('pkey'));
                    $select3->from(array('r' => 'formfields_radiocheck'));
                    $select3->where->notEqualTo('r.rc_value', $column_value);
                    $select3->where->equalTo('r.formfields_pkey', $row->pkey);
                    $statement3 = $sql->prepareStatementForSqlObject($select3);
                    //echo $select3->getSqlString();
                    $result3 = $statement3->execute();
                    $resultSet3 = new ResultSet();
                    $resultSet3->initialize($result3);
                    if(count($resultSet3) > 0)
                    {
                        foreach($resultSet3 as $res3){
                            $sql = new Sql($dbAdapter);		
                            $select4 = $sql->select();
                            $select4->columns(array('columns', 'type','dependent_field','is_add_more'));
                            $select4->from(array('f' => 'formfields'));
                            $select4->where('find_in_set ('.$res3->pkey.',f.dependent_column)');
                            //$select4->where->equalTo('f.dependent_column', '261');
                            $statement4 = $sql->prepareStatementForSqlObject($select4);
                            //echo $select4->getSqlString();
                            $result4 = $statement4->execute();
                            $resultSet4 = new ResultSet();
                            $resultSet4->initialize($result4);
                            if(count($resultSet4)>0){
                                foreach ($resultSet4 as $res4){
                                    if(!in_array($res4->columns, $data['result']['columns']))
                                    {
                                      $data['result']['columns'][]=$res4->columns;
                                      $data['result']['type'][] = $res4->type;
                                      $data['result']['display'][]  = 'none';
                                      $data['result']['is_required'][]  = 'false';
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {   
                    $dbAdapter = $this->tableGateway->getAdapter();	            
                    $select1 = 'select columns,type,dependent_field,is_add_more from formfields where dependent_field = "'.$row->pkey.'" and pkey NOT IN(select f.pkey from formfields f JOIN formfields_radiocheck r ON r.pkey = f.dependent_column where f.dependent_field = "'.$row->pkey.'" and r.rc_value = "'.$column_value.'")';
                    $statement1 = $dbAdapter->query($select1);
                    $result1 = $statement1->execute();
                    $resultSet1 = new ResultSet();
                    $resultSet1->initialize($result1);
                    foreach ($resultSet1 as $res1){
                        if($res1->is_add_more=='yes'){
                            $data['result']['columns'][] = $res1->columns.'[]';
                        } else {
                            $data['result']['columns'][] = $res1->columns;
                        }    
                        $data['result']['type'][] = $res1->type;
                        $parent_view = $this->getParentView($res1->dependent_field);                
                        if($parent_view == '0')
                        {
                            if($university == '3')
                            {
                                $data['result']['display'][]  = 'block';
                            }
                            else
                            {
                                $data['result']['display'][]  = 'none';
                            }
                        }
                        else
                        {
                            if($university == '3')
                            {
                                $data['result']['display'][]  = 'none';
                            }
                            else
                            {
                                $data['result']['display'][]  = 'none';
                            }
                        }
                        $data['result']['is_required'][]  = 'false';
                    }   
                }
			}
        }
        return $data;
     }
    public function getParentView($pkey) {
        
        $adapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($adapter);
        $sql = 'SELECT is_parentview FROM formfields WHERE pkey = "'.$pkey.'"';
        $statement = $adapter->query($sql);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet;
    }
    public function getUniversityID($domainName){
        $university_id = '';
        $dbAdapter = $this->tableGateway->getAdapter();	            
        $sql = 'SELECT id FROM configure_university WHERE subdomain_name="'.strtolower($domainName).'" ';
        $statement = $dbAdapter->query($sql);
        $result = $statement->execute();
        if(count($result)>0){
            foreach ($result as $res) {
                $university_id = $res['id'];
            }
        }
        return $university_id;        
    }
    public function getPartyinfo($pkey){
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $select = $sql->select();        
        $select->from(array('p' => 'partyinfo'));         
        $select->where->equalTo('p.pkey', $pkey); 
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        return $row; 
    }
    public function getEmailTemplate($type,$domainName,$job){
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);		
        $select = $sql->select();        
        $select->from(array('a' => 'applicant_email_template'));  
        $select->join(array('u' => 'configure_university'), 'u.id = a.university',array());        
        $select->where->equalTo('a.name', $type);   
        $select->where->equalTo('a.status', 1);  
        $select->where->equalTo('u.subdomain_name', $domainName);           
        $select->where(" FIND_IN_SET(".$job.",a.job)");        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->current();
        return $row;
    }
    
     /**
     * Generalized function for updated a single column in a single row of a given table
     * @param string $table
     * @param string $key
     * @param mixed $value
     * @param int $id
     * @return boolean true on success, false on failure, with error logged
     */
    public function updateTable($table, $key, $value, $id){
        $pkey  = (int) $id;     
        $dbAdapter = $this->tableGateway->getAdapter();		
        $sql = new Sql($dbAdapter);
        $update = $sql->update($table);
        $update->set(array($key => $value));
        $update->where("pkey = " . $pkey);
        $selectString = $sql->getSqlStringForSqlObject($update);
        $statement = $dbAdapter->query($selectString);
        try {
            $statement->execute();
            return true;
        } catch (Exception $e) {
            error_log(__FUNCTION__ . "() failed, table $table, key $key, value $value, pkey $pkey, error: " . $e->getMessage());
            return false;
        }
    }
        
    /**
     * get lookup value - for a given table, find the matching $lookup record, and return the specified $field
     * @param int $lookup - the matching pkey that we're finding on
     * @param string $table - table to look in
     * @param string $field - field to return
     * @return mixed - returns the specified field on success, false on failure
     */
    public function getLookupValue($lookup, $table, $field) {
        // since the values may, or may not, be JSON formatted (ie. [1,"", 1]) if 
        // so we'll recursively call this function, json_encode the returned values,
        // and send them back
        if (is_string($lookup) && json_decode($lookup) && substr($lookup, 0,1) == '[' 
                && substr($lookup,-1,1) ==']' && strpos($lookup, ',')) {
            $jsonValues = json_decode($lookup);
            if (count($jsonValues) > 0) {
                $jsonReturnValues = array();
                foreach ($jsonValues as $value) {
                    if(isset($value)){
                        $jsonReturnValues[] = self::getLookupValue($value, $table, $field);
                    } else {
                        $jsonReturnValues[] = '';
                    }
                }
                return json_encode($jsonReturnValues);
            }
        }
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        $select->columns(array('*'));
        $select->from(array('st' => $table));
        $select->where->equalTo('st.pkey', $lookup);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        if ($resultSet) {
            foreach ($resultSet as $res) {
                if (isset($res->$field)) {
                    return $res->$field;
                }
            }
        } else {
            return $lookup; // in the event we couldn't look up successfully, return the lookup value
        }

    }

}

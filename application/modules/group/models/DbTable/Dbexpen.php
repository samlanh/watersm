<?php

class Group_Model_DbTable_Dbexpen extends Zend_Db_Table_Abstract
{

    protected $_name = 'tbl_expense';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
   public function getExpenByID($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `tbl_expense` AS t WHERE t.expen_id=".$id;
		return $db->fetchRow($sql);
		print_r($sql);exit();
	}

    function addexpan($data){
    	$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$arr = array(
				'expen_title'=>$data['payfor'],
				'unit'=>$data['unit'],
				'price'=>$data['priceperunit'],
				'total_price'=>$data['total_Money'],
				'date_expen'=>$data['paid_day'],
				'description'=>$data['note'],
				'status'=>$data['status'],
				'village_id'=>$data['village_name'],
				'user_id'=>$this->getUserId(),
			);
		//	print_r($arr);exit();
			$this->_name='tbl_expense';
			$this->insert($arr);
			$db->commit();
			
		}catch(exception $e){
			//echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			$db->rollBack();
		}
	}


	function updateexpen($data){
		//print_r($data);exit();
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$where=" expen_id=".$data['id'];
			$arr = array(
				'expen_title'=>$data['payfor'],
				'unit'=>$data['unit'],
				'price'=>$data['priceperunit'],
				'total_price'=>$data['total_Money'],
				'date_expen'=>$data['paid_day'],
				'description'=>$data['note'],
				'village_id'=>$data['village_name'],
				'status'=>$data['status'],
				'user_id'=>$this->getUserId(),

			);
			$this->_name='tbl_expense';
			$this->update($arr,$where);
			$db->commit();
			//print_r($arr);exit();
		}catch(exception $e){
			//echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			$db->rollBack();
		}

	}

function geteAllexpen($search=null){
	$db = $this->getAdapter();
	$sql='SELECT e.expen_id,e.expen_title,e.unit,e.price,e.total_price,e.date_expen,
	e.description,e.`status`,
(SELECT v.village_namekh FROM ln_village AS v WHERE e.village_id =v.vill_id) AS village,
(SELECT u.user_name FROM rms_users AS u WHERE u.id=user_id ) AS USER FROM tbl_expense AS e 
WHERE 1';
		$where=" ";
		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search=$search['adv_search'];
			$s_where[]="expen_title  LIKE'%{$s_search}%'";
			$s_where[]="unit  LIKE'%{$s_search}%'";
			$s_where[]="price  LIKE'%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		} 
		if(!empty($search['village_name'])){
			$where.=" AND village_id = ".$search['village_name'];
		}
		$order = " ORDER BY expen_id DESC ";
		return $db->fetchAll($sql.$where.$order); 

	}
}


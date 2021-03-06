<?php

class Group_Model_DbTable_DbSettingprice extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_settingprice';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }
    public function getSettingpriceTopById(){
    	$db = $this->getAdapter();
    	$sql = "SELECT t.setId FROM `tb_settingprice` AS t ORDER By setId DESC limit 1";
    	return $db->fetchRow($sql);
    	//print_r($sql);exit();
    }
    function addsetting($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	
    	try{
    	$topID=$this->getSettingpriceTopById();
    		$arr=array('status'=>0);
    		$where="setId='.$topID.'";
    		$this->_name='tb_settingprice';
    		$this->update($arr,$where);
    		

	    	$arr = array(
	    			'price'=>$data['price'],
	    			'service_price'=>$data['service_price'],
	    			'date_start'=>$data['date_start'],
	    			'date_stop'=>$data['date_stop'],
	    			'deadline'=>$data['deadline'],
	    			'note'=>$data['note'],
	    			'status'=>$data['status'],
	    			'create_date' => date("Y-m-d"),
					'maintanance_service'=>$data['maintanance_service'],
	    			'user_id'=>$this->getUserId(),
					'earning_start'=>$data['earning_start'],
					'earning_stop'=>$data['earning_stop'],
	    		);
	    	$this->_name='tb_settingprice';
	        $this->insert($arr);
	    	$db->commit();
	    	

	    	
    	}catch(exception $e){
    		//echo $e->getMessage();exit();
    		Application_Form_FrmMessage::message("Application Error");
    		Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
    		$db->rollBack();
    	}
	}
	function updatesetting($data){
		//print_r($data);exit();
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$where=" setId=".$data['id'];
			$arr = array(
					'price'=>$data['price'],
					'maintanance_service'=>$data['maintanance_service'],
					'service_price'=>$data['service_price'],
	    			'date_start'=>$data['date_start'],
	    			'date_stop'=>$data['date_stop'],
	    			'deadline'=>$data['deadline'],
	    			'note'=>$data['note'],
	    			'status'=>$data['status'],
	    			'user_id'=>$this->getUserId(),
					'earning_start'=>$data['earning_start'],
					'earning_stop'=>$data['earning_stop'],

			);
			$this->_name='tb_settingprice';
			$this->update($arr, $where);
			$db->commit();
			
		}catch(exception $e){
			//echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			$db->rollBack();
		}
	}

	function clientPay ($client_pay_id){
		$db = $this->getAdapter();
		$sql="SELECT * FROM  tb_used AS u WHERE u.client_num=".$client_pay_id;
		return $db->fetchRow($sql);
	}
	
function geteAllSettingprice($search=null){
		$db = $this->getAdapter();
		$sql='select setId,price,service_price,maintanance_service,date_start,date_stop,deadline,note,status,
				earning_start,earning_stop,
		(select u.user_name from rms_users as u where u.id=user_id limit 1) as user from tb_settingprice  Where 1';
		 $where="  ";
	    /*$from_date =(!empty($search['Datesearch_start']))? '1': " date_start >= '".$search['Datesearch_start']." 00:00:00'";
		$to_date = (!empty($search['Datesearch_stop']))? '1': " date_stop <= '".$search['Datesearch_stop']." 23:59:59'";
		$where = " AND ".$from_date." AND ".$to_date;  */ 
	
		if($search['status_search']>-1){
			$where.=" AND status=".$search['status_search'];
		}

		if(!empty($search['adv_search'])){
			$s_where=array();
			$s_search=$search['adv_search'];
			$s_where[]="note  LIKE'%{$s_search}%'";
			$s_where[]="price  LIKE'%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		} 
		
		$order = " ORDER BY setId DESC ";
		//echo $sql.$where;
		return $db->fetchAll($sql.$where.$order); 

	}
	
	
	public function getSettingpriceById($id){
		$db = $this->getAdapter();
		$sql = "SELECT * FROM `tb_settingprice` AS t WHERE t.`setid`=".$id;
		return $db->fetchRow($sql);
		//print_r($sql);exit();
	}
	/* function ajaxPropertytype($data){ // used on ProperiestypeController
		$this->_name='tb_settingprice';
		$db = $this->getAdapter();
		$arr = array(
				'price'=>$data['price'],
				'status'=>1
		);
		return $this->insert($arr);
	} */
	
}


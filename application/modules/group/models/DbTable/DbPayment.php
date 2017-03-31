<?php

class Group_Model_DbTable_DbPayment extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_settingprice';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    
    }

	function addPayment($searchdata){

		//print_r($searchdata);exit();

		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$arr = array(
				'client_id'=>$searchdata['customername_id'],
				'total_payment'=>$searchdata['total_full_pay'],
				'payment_month'=>$searchdata['moneyto_pay'],
				'input_pay'=>$searchdata['input_money'],
				'owed_last_month'=>$searchdata['old_owed'],
				'owed_next_month'=>$searchdata['new_owed'],
				'used_id'=>$this->getUserId(),
				'seting_price_id'=>$searchdata['seting_price_id'],
			'village_id'=>$searchdata['village_id'],
			'used_id'=>$searchdata['used_id'],

			);
			$this->_name='tbl_payment';
			$this->insert($arr);
			$db->commit();

		}catch(exception $e){
			//echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			$db->rollBack();
		}


	}

    function addsetting($data){
    	$db = $this->getAdapter();
    	$db->beginTransaction();
    	try{
	    	$arr = array(
	    			'price'=>$data['price'],
	    			'service_price'=>$data['service_price'],
	    			'date_start'=>$data['date_start'],
	    			'date_stop'=>$data['date_stop'],
	    			'deadline'=>$data['deadline'],
	    			'note'=>$data['note'],
	    			'status'=>$data['status'],
	    			'user_id'=>$this->getUserId(),
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
					'service_price'=>$data['service_price'],
	    			'date_start'=>$data['date_start'],
	    			'date_stop'=>$data['date_stop'],
	    			'deadline'=>$data['deadline'],
	    			'note'=>$data['note'],
	    			'status'=>$data['status'],
	    			'user_id'=>$this->getUserId(),
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
	
	
	
	
function geteAllSettingprice($search=null){
		$db = $this->getAdapter();
		$sql='select setId,price,service_price,date_start,date_stop,deadline,note,status,(select u.user_name from rms_users as u where u.id=user_id ) as user from tb_settingprice  Where 1';
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
	
	public function get_client_pay($search=null){
		try{
			$db=$this->getAdapter();
			$sql="
		SELECT u.client_num ,
				u.client_id,u.user_id,u.village_id,
				u.seting_price_id,
			(SELECT s.price FROM tb_settingprice AS s WHERE s.setId=u.seting_price_id LIMIT 1) AS price,
	(SELECT v.village_name FROM ln_village AS v WHERE v.vill_id=u.village_id LIMIT 1) AS village_name,
	(SELECT l.name_kh FROM ln_client AS l WHERE u.client_id=l.client_id LIMIT 1) AS name_kh,
	(SELECT l.phone FROM ln_client AS l WHERE u.client_id=l.client_id LIMIT 1) AS phone,
	(SELECT s.date_stop FROM tb_settingprice AS s WHERE s.setId=u.seting_price_id LIMIT 1) AS date_stop,
	(SELECT s.date_start FROM tb_settingprice AS s WHERE s.setId=u.seting_price_id LIMIT 1) AS date_start,
	(SELECT s.deadline FROM tb_settingprice AS s WHERE s.setId=u.seting_price_id LIMIT 1) AS deadline,
				
				u.id,		
				u.total_price,
				u.id,
				u.stat_use,
				u.end_use			
			FROM 
				tb_used AS u 			
			
			";
			$where=" where 1 ";
			if(!empty($search['code_search'])){
				//print_r($search['code_search']);exit();
				$where.=" AND u.client_num ='".$search['code_search']."'";
				if ($search['code_search']!=$where){
					echo "<script>alert('លេខកូដដែលលោកអ្នកបញ្ចូលមិនមាននៅក្នុងប្រព័ន្ធទេ');</script>";
				}
			}else{
				$where.=" AND u.client_num ='0'";
			}

			$where.=" ORDER BY id DESC LIMIT 1";
			return $db->fetchRow($sql.$where);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
	}
	function getAllClients($search = null){
		try{
			$db = $this->getAdapter();
			$sql="SELECT client_id,client_number,name_kh,sex,
(SELECT  v.village_name FROM ln_village AS v WHERE v.vill_id = village_id LIMIT 1)
village_id,phone,status,date_cus_start FROM ln_client";
			$where=" where 1";
			if(!empty($search['adv_search'])){
				$s_where = array();
				$s_search = addslashes(trim($search['adv_search']));
				$s_where[] = " name_kh LIKE '%{$s_search}%'";
				$s_where[] = " phone LIKE '%{$s_search}%'";
				$s_where[] = " village_id LIKE '%{$s_search}%'";
				$s_where[] = " street LIKE '%{$s_search}%'";
				$where .=' AND ('.implode(' OR ',$s_where).')';

			}

			/* 	 if($search['status']>-1){
                    $where.= " AND status = ".$search['status'];
                }
                 */
			if(!empty($search['search_village'])){
				$where.=" AND v.vill_id= ".$search['search_village'];
			}
			$order=" ORDER BY client_id DESC ";
			return $db->fetchAll($sql.$where.$order);
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	function geteAllpayment($search=null){
		$db = $this->getAdapter();
		$sql='
			SELECT 	u.client_id,u.seting_price_id,u.village_id,u.end_use,u.stat_use,u.total_use,u.total_price,u.ohter_fee,
					c.name_kh,c.client_number,s.date_start,s.date_stop,
			FROM tb_used AS u,tb_settingprice AS s,ln_client AS c 
			
			WHERE u.client_id=c.client_id AND u.seting_price_id=s.setId 
		';
		$where="   ";
		/*$from_date =(!empty($search['Datesearch_start']))? '1': " date_start >= '".$search['Datesearch_start']." 00:00:00'";
			$to_date = (!empty($search['Datesearch_stop']))? '1': " date_stop <= '".$search['Datesearch_stop']." 23:59:59'";
		$where = " AND ".$from_date." AND ".$to_date;  */
	 
		/*  if($search['client_id']){
			$where.=" AND client_id=".$search['code'];
		}   */

		$where = "  ";
		//echo $sql.$where;
		return $db->fetchAll($sql.$where);
	
	}
	
	
	
}


<?php

class Payment_Model_DbTable_DbPayment extends Zend_Db_Table_Abstract
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
				'client_id'=>$searchdata['client_id'],
				'total_payment'=>$searchdata['total_full_pay'],
				'payment_month'=>$searchdata['moneyto_pay'],
				'input_pay'=>$searchdata['input_money'],
				'owed_last_month'=>$searchdata['old_owed'],
				'owed_next_month'=>$searchdata['new_owed'],
				'used_id'=>$this->getUserId(),
				'seting_price_id'=>$searchdata['seting_price_id'],
				'village_id'=>$searchdata['village_id'],
				'used_id'=>$searchdata['used_id'],
				'date_input'=>$searchdata['date_input'],
				'maintanance_service'=>$searchdata['maintanance_service'],
				'user_id'=>$this->getUserId(),


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
	
	function geteAllpaymentlist($search=null){
		$db = $this->getAdapter();
		$sql='
			SELECT  
				p.`pay_id`,(SELECT c.`client_number` FROM `ln_client` AS c WHERE c.client_id =p.`client_id`) AS client_id,
				(SELECT c.`name_kh` FROM `ln_client` AS c WHERE c.client_id =p.`client_id`) AS name_kh,
				(SELECT  v.village_name FROM ln_village AS v WHERE v.vill_id = p.village_id ) AS village,
				p.`total_payment`,p.`payment_month`,p.`owed_last_month`,p.`input_pay`,
				(SELECT us.stat_use FROM `tb_used` AS us  WHERE us.id =p.used_id) AS stat_use,
				(SELECT us.end_use FROM `tb_used` AS us  WHERE us.id =p.used_id) AS end_use, 
				(SELECT s.price FROM `tb_settingprice` AS s WHERE s.setId =p.`seting_price_id` ) AS settingprice,
				(SELECT u.`first_name`  FROM `rms_users` AS u WHERE u.`id`= p.`user_id`) AS user_id 
			FROM `tbl_payment` AS p 
			WHERE `pay_note`=1
		';

		$where = "  ";
		if(!empty($search['search_option_number'])){
			//$where.=" AND client_id='209' ";
		$where.=" AND (SELECT cl.client_number FROM ln_client AS cl WHERE p.client_id=cl.client_id LIMIT 1)='".$search['search_option_number']."'";
		}
		/* if (!empty($search['adv_search'])){
			$s_where = array();
			$s_search = trim(addslashes($search['adv_search']));
			$s_where[] = " name_kh LIKE '%{$s_search}%'";
			$s_where[] = " village LIKE '%{$s_search}%'";
			$where .=' AND ('.implode(' OR ',$s_where).')';
		} */
		return $db->fetchAll($sql.$where);
	
	}

	function getVillageByAjax($vid){
		$db = $this->getAdapter();
		$sql="SELECT v.`code`,v.`village_namekh` FROM `ln_village`AS v WHERE v.`vill_id`=".$vid;
		return $db->fetchRow($sql);
	}
	function getNumerClient($client){
		//print_r($client);exit();
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
	(SELECT s.maintanance_service FROM tb_settingprice AS s WHERE s.setId=u.seting_price_id LIMIT 1) AS maintanance_service,
				u.id,		
				u.total_price,
				u.id,
				u.stat_use,
				u.end_use			
			FROM 
				tb_used AS u where u.client_num='".$client."'
				order by u.id desc limit 1
			
			"	;
//$condition="	tb_used AS u 	where u.client_num='".$client."'";
		return $db->fetchRow($sql);
	}

function getListPayment($search=null){
					$db=$this->getAdapter();
					$sql="
			SELECT
			pa.pay_id ,
			
				(SELECT cl.client_number FROM ln_client AS cl WHERE pa.client_id=cl.client_id LIMIT 1) AS client_num,
				(SELECT cl.name_kh FROM ln_client AS cl WHERE pa.client_id=cl.client_id LIMIT 1) AS name_kh,
				pa.input_pay,
				pa.total_payment,
				pa.payment_month,
				pa.owed_last_month,
				pa.owed_next_month,
			  	(SELECT v.village_namekh FROM ln_village AS v WHERE v.vill_id=pa.village_id LIMIT 1 )AS village_namekh	
				,pa.date_input,
				(select usr.user_name From rms_users as usr where pa.user_id=usr.id limit 1) as user_name
			FROM
				tbl_payment AS pa
					";
			$where=" where 1 ";
			$where1="
			WHERE
				(SELECT cl.client_number FROM ln_client AS cl WHERE pa.client_id=cl.client_id LIMIT 1)='c0210' 
			
			";

	if(!empty($search['search_option_number'])){
		//$where.=" AND client_id='209' ";
	$where.=" AND (SELECT cl.client_number FROM ln_client AS cl WHERE pa.client_id=cl.client_id LIMIT 1)='".$search['search_option_number']."'";
	}
	$order=" ORDER BY pa.pay_id DESC";
			return $db->fetchAll($sql.$where.$order);
	}
	public function getPaymentById($id){
		$db = $this->getAdapter();
		$sql = "
		
		 	SELECT	
		 	pa.pay_id,
			(Select ln.name_kh From ln_client as ln WHERE ln.client_id=pa.client_id limit 1) as client_kh,
			(Select ln.client_number From ln_client as ln where ln.client_id=pa.client_id limit 1) as client_number,
			pa.total_payment,pa.payment_month,pa.input_pay,pa.owed_last_month,
			pa.owed_next_month,pa.maintanance_service,pa.date_input,
			
			(Select s.price From tb_settingprice AS s WHERE s.setId=pa.seting_price_id limit 1 ) AS Sett_price,
			(Select v.village_name From ln_village AS v WHERE v.vill_id=pa.village_id limit 1 ) AS village,
			(Select ln.phone From ln_client as ln where ln.client_id=pa.client_id limit 1) as phone_number,
			(Select s.date_start From tb_settingprice AS s WHERE s.setId=pa.seting_price_id limit 1 ) AS date_start,
			(Select s.date_stop From tb_settingprice AS s WHERE s.setId=pa.seting_price_id limit 1 ) AS date_stop,
			(Select s.deadline From tb_settingprice AS s WHERE s.setId=pa.seting_price_id limit 1 ) AS deadline,
			(Select u.total_price From tb_used AS u WHERE u.id=pa.used_id limit 1 ) AS total_price,
			(Select u.stat_use From tb_used AS u WHERE u.id=pa.used_id limit 1 ) AS stat_use,
			(Select u.end_use From tb_used AS u WHERE u.id=pa.used_id limit 1 ) AS end_use
			
 			From tbl_payment as pa
		
		
		  WHERE pa.`pay_id`=".$id;
		return $db->fetchRow($sql);

	}

	function updatePayment($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
			$where="pay_id=".$data['id'];
			$arr = array(
				
				'input_pay'=>$data['input_money'],
				'date_input'=>$data['date_input'],
				'user_id'=>$data['user_id']
				
			);

			$this->_name='tbl_payment';
			$this->update($arr, $where);
			//print_r($this->update($arr, $where));exit();
			$db->commit();

		}catch(exception $e){
			//echo $e->getMessage();exit();
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			$db->rollBack();
		}
	}

	
	
}


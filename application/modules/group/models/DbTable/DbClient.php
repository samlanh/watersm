<?php

class Group_Model_DbTable_DbClient extends Zend_Db_Table_Abstract
{

    protected $_name = 'ln_client';
	//protected $_name_used="tb_used";
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
	public function updateClient($_data){


		//	print_r($_data);exit();
		try{
			//print_r($_data['id']);exit();
			//	$client_code = $this->getClientCode();
			$_arr=array(
				'client_number'=>$_data['client_no'],
				'name_kh'	  => $_data['name_kh'],
				'sex'	      => $_data['sex'],
				'phone'	      => $_data['phone'],
				'price_service'=>$_data['price_service'],
				'village_id'  => $_data['village_1'],
				'remark'	  => $_data['desc'],
				'status'      => $_data['status'],
				'date_cus_start'=>$_data['date_cus_start'],
				'unit_price'=>$_data['unit_price'],
				'total_service'=>$_data['total_service'],
				'user_id'=>$this->getUserId(),

			);
				$where = 'client_id = '.$_data['id'];
				$this->update($_arr, $where);
				return $_data['id'];

		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
	public function addClient($_data){


	//	print_r($_data);exit();
		try{
			//print_r($_data['id']);exit();
		//	$client_code = $this->getClientCode();
			$_arr=array(
				'client_number'=>$_data['client_no'],
				'name_kh'	  => $_data['name_kh'],
				'sex'	      => $_data['sex'],
		    	'phone'	      => $_data['phone'],
				'price_service'=>$_data['price_service'],
				'village_id'  => $_data['village_1'],
				'remark'	  => $_data['desc'],
				'status'      => $_data['status'],
		    	'date_cus_start'=>$_data['date_cus_start'],
				'price_service'=>$_data['price_service'],
				'unit_price'=>$_data['unit_price'],
				'total_service'=>$_data['total_service'],
				'user_id'=>$this->getUserId(),

		);


		if(!empty($_data['id'])){

			$where = 'client_id = '.$_data['id'];
			$this->update($_arr, $where);
			return $_data['id'];

		}else{

			return  $this->insert($_arr);
		}
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
	}
/*	public function getIdClient($id){
		$db = $this->getAdapter();
		$sql = "SELECT client_id FROM $this->_name ORDER BY client_id DESC";
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}*/
	public function getUserById($id){
	
	}

	public function getLastClient(){
		$db = $this->getAdapter();
		$sql = "SELECT client_id FROM ln_client ORDER BY client_id DESC LIMIT 1 ";
		$row=$db->fetchRow($sql);
		print_r($row);exit();
		return $row;
	}

	public function addFirstUsed($_data){
		try{
			//	$client_code = $this->getClientCode();
			$_arr=array(
				'client_id'=>$_data['id'],
				'end_use'	  => $_data['end_used'],
				'user_id'=>$this->getUserId(),
				'new'=>'1',
				'village_id'  => $_data['village_1'],
				'seting_price_id'=>$_data['price_per_use_id'],
				'create_date' => $_data['date_cus_start'],
				'statust'=> $_data['status'],
				'client_num'=>$_data['client_no']
			);

			$this->_name= "tb_used";
			return  $this->insert($_arr);
			
		}catch(Exception $e){
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());

		}
	}
	public function getEditClientById($id){
		$db = $this->getAdapter();
		$sql = "
		SELECT ln.client_id,ln.client_number,ln.name_kh,ln.sex,
		ln.remark,ln.create_date,ln.user_id,
		ln.village_id,ln.phone,
		ln.remark,
		ln.status,
		(SELECT v.vill_id FROM ln_village AS v WHERE ln.village_id=v.vill_id LIMIT 1) AS vill_id,
		(SELECT v.code FROM ln_village AS v WHERE ln.village_id=v.vill_id LIMIT 1) AS vill_code,
		ln.unit_price,ln.total_service,ln.total_service,ln.price_service 
		
		FROM $this->_name as ln WHERE client_id = 
				".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getClientById($id){
		$db = $this->getAdapter();
		$sql = "SELECT client_id,name_kh,sex,village_id,phone,remark,status,date_cus_start FROM $this->_name WHERE client_id = ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getClientDetailInfo($id){
		$db = $this->getAdapter();
		$sql = "SELECT c.client_id , c.client_number ,c.`name_en`,c.`name_kh`,c.`sex`,
		c.`id_number`,c.`id_type`,c.`acc_number`,c.`phone`,c.`dob`,c.`pob`,c.`tel`,c.`email`,c.`street`,c.`house`,
		(SELECT v.name_kh FROM `ln_view` AS v WHERE v.type=23 AND v.key_code = c.`client_d_type`) AS doc_name,
		(SELECT commune_namekh FROM `ln_commune` WHERE com_id = c.com_id   LIMIT 1) AS commune_name
		,(SELECT district_namekh FROM `ln_district` AS ds WHERE dis_id = c.dis_id  LIMIT 1) AS district_name
		,(SELECT province_kh_name FROM `ln_province` WHERE province_id= c.pro_id  LIMIT 1) AS province_en_name
		,(SELECT village_namekh FROM `ln_village` WHERE vill_id = c.village_id  LIMIT 1) AS village_name , 
		(SELECT project_name FROM `ln_project` WHERE br_id =c.branch_id LIMIT 1) AS project_name ,
		 c.`remark`,c.`status`,
		 c.bname_kh,c.`hname_kh`,c.`lphone`,c.`ksex`,
		 (SELECT commune_namekh FROM `ln_commune` WHERE com_id = c.dcommune   LIMIT 1) AS p_commune_name
		,(SELECT district_namekh FROM `ln_district` AS ds WHERE dis_id = c.adistrict  LIMIT 1) AS p_district_name
		,(SELECT province_kh_name FROM `ln_province` WHERE province_id= c.cprovince  LIMIT 1) AS p_province_en_name
		,(SELECT village_namekh FROM `ln_village` WHERE vill_id = c.qvillage  LIMIT 1) AS p_village_name ,
		c.`dstreet`,c.`ghouse`,c.`nationality`,c.`nation_id`,c.`p_nationality`,c.`rid_no`,c.`arid_no`,c.refe_nation_id,
		(SELECT v.name_kh FROM `ln_view` AS v WHERE v.type=23 AND v.key_code = c.`joint_doc_type`) AS join_doc_name,
		 c.photo_name FROM `ln_client` AS c WHERE client_id =  ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	public function getClientCallateralBYId($client_id){
		$db = $this->getAdapter();
		$sql = " SELECT cc.id AS client_coll ,cd.* FROM `ln_client_callecteral` AS cc , `ln_client_callecteral_detail` AS cd WHERE  
		         cd.is_return=0 AND cd.client_coll_id = cc.id AND cc.client_id = ".$client_id;
		return $db->fetchAll($sql);
	}
    function getViewClientByGroupId($group_id){
    	$db = $this->getAdapter();
    	$sql=" SELECT * FROM $this->_name WHERE client_id=
    	(SELECT client_id FROM `ln_loan_member` WHERE group_id=".$db->quote($group_id)." LIMIT 1)";
    	$row=$db->fetchRow($sql);
    	return $row;
    }
	function getAllClients($search = null){
		try{
			$db = $this->getAdapter();
			$sql="SELECT l.client_id,l.client_number,l.name_kh,l.sex,
	(SELECT  v.village_name FROM ln_village AS v WHERE v.vill_id = l.village_id LIMIT 1) AS village,
l.phone,l.status,l.date_cus_start FROM ln_client AS l";
			$where=" where 1";
			if(!empty($search['adv_search'])){
				$s_where = array();
				$s_search = addslashes(trim($search['adv_search']));
				$s_where[] = " client_number LIKE '%{$s_search}%'";
				$s_where[] = " name_kh LIKE '%{$s_search}%'";
				$s_where[] = " phone LIKE '%{$s_search}%'";
				$s_where[] = " village_id LIKE '%{$s_search}%'";
				$s_where[] = " street LIKE '%{$s_search}%'";
				$where .=' AND ('.implode(' OR ',$s_where).')';
			}

		 	 if($search['status']>-1){
				$where.= " AND status = ".$search['status'];
			}

			if(!empty($search['search_village'])){
				$where.=" AND l.village_id= ".$search['search_village'];
			}
			$order=" ORDER BY client_id DESC ";
			return $db->fetchAll($sql.$where.$order);
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	public function getGroupCodeBYId($data){
		$db = $this->getAdapter();
			$sql = " SELECT *,
				(SELECT t.type_nameen FROM `ln_properties_type` as t WHERE t.id=property_type) As property_type
				FROM `ln_properties` 
			WHERE id = ".$data['land_id']." LIMIT 1" ;
			 $rs = $db->fetchRow($sql);
			 $rs['house_type']=ltrim(strstr($rs['property_type'], '('), '.');

			if(empty($rs)){return ''; }else{
				return $rs;
			}

	}
	public function get_base_service(){
		$db=$this->getAdapter();
		$sql="SELECT service_price FROM tb_settingprice WHERE status='1' order by setId desc limit 1";
		return $db->fetchOne($sql);

	}
	public function get_cus_start(){
		$db=$this->getAdapter();
		$sql="SELECT date_start FROM tb_settingprice WHERE status='1' order by setId desc limit 1";
		return $db->fetchOne($sql);

	}
	public function get_setting_id(){
		$db=$this->getAdapter();
		$sql="SELECT setId FROM tb_settingprice WHERE status='1' order by setId desc limit 1";
		return $db->fetchOne($sql);
	}
	public function get_setting_price(){
		$db=$this->getAdapter();
		$sql="SELECT price FROM tb_settingprice WHERE status='1' order by setId desc limit 1";
		return $db->fetchOne($sql);

	}
	public function get_price_per_used(){
		$db=$this->getAdapter();
		$sql="SELECT setId FROM tb_settingprice WHERE status='1' order by setId desc limit 1";
		return $db->fetchOne($sql);

	}

	function getPrefixCode($branch_id){
		$db  = $this->getAdapter();
		$sql = " SELECT prefix FROM `ln_branch` WHERE br_id = $branch_id  LIMIT 1";
		return $db->fetchOne($sql);
	}
	public function getClientCode(){//for get client by branch
		$db = $this->getAdapter();
			$sql = "SELECT COUNT(client_id) AS number FROM `ln_client`
			WHERE 1 ";
		$acc_no = $db->fetchOne($sql);
		$new_acc_no= (int)$acc_no+1;
		$acc_no= strlen((int)$acc_no+1);
		$pre ="";
		for($i = $acc_no;$i<4;$i++){
			$pre.='0';
		}
		return $pre.$new_acc_no;
	}
// 	public function adddoocumenttype($data){

// 		$db = $this->getAdapter();
// 		$document_type=array(
// 				'name_en'=>$data['clienttype_nameen'],
// 				'name_kh'=>$data['clienttype_namekh'],
// 				'displayby'=>1,
// 				'type'=>23,
// 				'status'=>1

// 		);

// 		$row= $this->insert($document_type);
// 		return $row;
// 	}
	public function addIndividaulClient($_data){

		$client_code = $this->getClientCode($_data['branch_id']);
			$_arr=array(
					'is_group'=>0,
					'group_code'=>'',
					'parent_id'=>0,
					'client_number'=>$client_code,
					'name_kh'	  => $_data['name_kh'],
					'name_en'	  => $_data['name_en'],
					'sex'	      => $_data['sex'],
					'sit_status'  => $_data['situ_status'],
					'dis_id'      => $_data['district'],
					'village_id'  => $_data['village'],
					'street'	  => $_data['street'],
					'house'	      => $_data['house'],
					'branch_id'  => $_data['branch_id'],
					'job'        =>$_data['job'],
					'phone'	      => $_data['phone'],
					'create_date' => date("Y-m-d"),
					'client_d_type'      => $_data['client_d_type'],
					'user_id'	  => $this->getUserId(),
					'dob'			=>$_data['dob_client'],
					'pro_id'      => $_data['province'],
					'com_id'      => $_data['commune'],


			);

				$this->_name = "ln_client";
				$id =$this->insert($_arr);
				return array('id'=>$id,'client_code'=>$client_code);
	}

	function addViewType($data){
		try{
			$db = $this->getAdapter();
			$data['type'] = 23;
			$key_code = $this->getLastKeycodeByType($data['type']);
			$arr = array(
				'name_kh'		=>$data['doc_name'],
				'status'		=>1,
				'displayby'		=>1,
				'key_code'		=>$key_code,
				'type'			=>$data['type'],
			);
			$this->_name = "ln_view";
			return $this->insert($arr);
		}catch (Exception $e){
			echo '<script>alert('."$e".');</script>';
		}
	}

	function getLastKeycodeByType($type){
		$db =$this->getAdapter();
		$sql = "SELECT key_code FROM `ln_view` WHERE type=$type ORDER BY key_code DESC LIMIT 1 ";
		$number = $db->fetchOne($sql);
		return $number+1;
	}

	function getVillagOpt(){
		$db =$this->getAdapter();
		$sql ="SELECT v.`vill_id` AS id, v.`village_name` AS NAME FROM ln_village AS v WHERE	v.`status`=1";
		return $db->fetchAll($sql);
	}

	function getVillageByAjax($vid){
		$db = $this->getAdapter();
		$sql="SELECT v.`code`,v.`village_namekh` FROM `ln_village`AS v WHERE v.`vill_id`=".$vid;
		return $db->fetchRow($sql);
	}
	function getClientInforByAjax($village_id){
		$db = $this->getAdapter();
		/* $sql="
		SELECT c.`name_kh`,c.`client_id`,u.`stat_use` FROM `ln_client` AS c ,tb_used AS u WHERE c.`village_id`=$village_id

		"; */
		$sql="SELECT c.`name_kh`,c.`client_id` FROM `ln_client` AS c WHERE c.`village_id`=$village_id";
		return  $db->fetchAll($sql);
	}
		function getClientInfo($village_id){
			$db = $this->getAdapter();
				$testwhere="WHERE u.create_date>=(SELECT s.date_start FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id AND STATUS=1 ORDER BY setId DESC LIMIT 1)
	AND 
		u.create_date<=(SELECT s.date_stop FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id AND STATUS=1 ORDER BY setId DESC LIMIT 1)
	";
			$sql="

			SELECT 
				  	(SELECT l.name_kh FROM ln_client AS l WHERE l.client_id=u.client_id LIMIT 1) AS name_kh,
					  (SELECT l.village_id FROM ln_client AS l WHERE l.village_id=u.village_id LIMIT 1) AS village_id,
					  (SELECT v.village_namekh FROM ln_village AS v WHERE v.vill_id=u.village_id LIMIT 1) AS village_namekh,
					  (SELECT s.setId FROM tb_settingprice AS s ORDER BY s.setId DESC LIMIT 1)AS setId,	
							u.client_id,u.client_num,u.stat_use,u.end_use,u.total_use,u.total_price,u.id,

					  (SELECT s.price FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id LIMIT 1) AS price,
					  (SELECT s.date_start FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id  ORDER BY setId DESC LIMIT 1) AS date_start,
	  				  (SELECT s.date_stop FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id  ORDER BY setId DESC LIMIT 1) AS date_stop
 			FROM tb_used AS u
 	WHERE 
 	 u.create_date>=(SELECT s.date_start FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id AND STATUS=1 ORDER BY setId DESC LIMIT 1)
	AND 
		u.create_date<=(SELECT s.deadline FROM tb_settingprice AS s   WHERE s.setId=u.seting_price_id AND STATUS=1 ORDER BY setId DESC LIMIT 1)
	AND
 	u.village_id=$village_id 
 	AND u.new =1
	";
			return  $db->fetchAll($sql);
		}
	function addused($data){

	 $db = $this->getAdapter();
     $db->beginTransaction();
     try{
      $arr = array(
        'client_id'=>$data['client_id'],
        'stat_use'=>$data['date'],
        'end_use'=>$data['note'],
       );
      $this->_name='tb_used';
         $this->insert($arr);
      $db->commit();

     }catch(exception $e){
      //echo $e->getMessage();exit();
      Application_Form_FrmMessage::message("Application Error");
      Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
      $db->rollBack();
     }
	}
}


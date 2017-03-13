<?php

class Other_Model_DbTable_DbVillage extends Zend_Db_Table_Abstract
{

    protected $_name = 'ln_village';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    	 
    }
	public function addVillage($_data){
		$_arr=array(
				'code'	  => $_data['code'],
/*				'commune_id'	  => $_data['commune_name'],
*/				/*'village_name'	  => $_data['village_name'],*/
				'village_namekh'	  => $_data['village_namekh'],
/*				'displayby'	  => $_data['display'],*/
				'status'	  => $_data['status'],
				'modify_date' => Zend_Date::now(),
				'user_id'	  => $this->getUserId()
				
		);
		if(!empty($_data['id'])){
			
			$where = 'vill_id = '.$_data['id'];
			return  $this->update($_arr, $where);
		}else{
			return  $this->insert($_arr);
		}
		
	}

	function addVillageByAjax($_data){
		$db = $this->getAdapter();
		$_arr=array(
				'commune_id'	  => $_data['commune_name'],
				'village_name'	  => $_data['village_name'],
				'village_namekh'	  => $_data['village_namekh'],
				'displayby'	  => $_data['display'],
				'status'	  => $_data['status'],
				'modify_date' => Zend_Date::now(),
				'user_id'	  => $this->getUserId()
		);
		return  $this->insert($_arr);
	}
	public function getVillageById($id){
		$db = $this->getAdapter();
		$sql=" SELECT *  FROM  `ln_village` AS v where v.vill_id= ".$db->quote($id);
		$sql.=" LIMIT 1 ";
		$row=$db->fetchRow($sql);
		return $row;
	}
	function getAllVillage($search=null){
		$db = $this->getAdapter();
// 		$sql =" CALL st_getAllVillage('',1) ";
		$sql ="SELECT	v.vill_id,v.code,v.village_namekh,v.modify_date,(select s.name_en from tb_view as s where key_code=v.status and s.type=5) as status ,(select u.first_name from rms_users as u where u.id=v.user_id ) as  user
				FROM ln_village AS v";
		$where = '';
//         if($search['province_name']>=0){
//         	$where.= " AND p.province_id = ".$search['province_name'];
//         }
//         if(!empty($search['district_name'])){
//         	$where.= " AND d.dis_id = ".$search['district_name'];
//         }
//         if($search['commune_name']>0){
//         	$where.= " AND c.com_id = ".$search['commune_name'];
//         }
        
// 		if($search['search_status']>-1){
// 			$where.= " AND v.status = ".$search['search_status'];
// 		}
// 		if(!empty($search['adv_search'])){
// 			$s_where = array();
// 			$s_search = $search['adv_search'];
// 			$s_where[] = " v.village_name LIKE '%{$s_search}%'";
// 			$s_where[]=" v.village_namekh LIKE '%{$s_search}%'";
// 			$where .=' AND ('.implode(' OR ',$s_where).')';
// 		}
		
		$order= ' ORDER BY v.vill_id DESC ';
		return $db->fetchAll($sql.$where.$order);	
	}
       public function getAllvillagebyCommune($village_id){
		$db = $this->getAdapter();
		$sql = "SELECT vill_id AS id,village_namekh AS name FROM $this->_name WHERE village_name!='' AND status=1 AND commune_id=".$db->quote($village_id);
		$rows=$db->fetchAll($sql);
		return $rows;
	}	
}

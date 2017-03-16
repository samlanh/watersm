<?php

class Loan_Model_DbTable_DbPayUsed extends Zend_Db_Table_Abstract
{
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;

	}
	public function addDetailUsed($data){
		$db = $this->getAdapter();
		$userID= $this->getUserId();
	///	print_r($userID);exit();
		$db->beginTransaction();
		try{
	
			$identity_su = $data["record_row"];
			$idsu = explode(',', $identity_su);
	
			foreach ($idsu as $i){
				$arr_item =array(
					'user_id'=>$this->getUserId(),
					'client_id'=>$data['client_id'.$i],
					'client_num'=>$data['client_num'.$i],
					'stat_use'=>$data['new_num'.$i],
					'total_use'=>$data['toatal_water'.$i],
					'statust'=>'1',
					'village_id'=>$data['village_1_ed'],
					'total_price'=>$data['total_price'.$i],
					'price_set_id'=>$data['price_setting'.$i],
				);
				$this->_name= "tb_used";
				$this->insert($arr_item);
	
	
			}
			//exit();
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e);
			echo $e->getMessage();
		}
		 
	}
}

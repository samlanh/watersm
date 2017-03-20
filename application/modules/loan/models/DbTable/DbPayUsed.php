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
	//	print_r($data);exit();
		$db->beginTransaction();
		try{
			$identity_su = $data["record_row"];
			$idsu = explode(',', $identity_su);
			foreach ($idsu as $i){

				$arr =array(
					'new'=> 0,
				);
				$this->_name= "tb_used";
				$where=" id ='".$data['id'.$i]."'" ;


				$this->update($arr, $where);
				
			}
			foreach ($idsu as $i){
				$start_used=$data['new_num'.$i]-$data['toatal_water'.$i];
				$arr_item =array(
					'user_id'=>$this->getUserId(),
					'client_id'=>$data['client_id'.$i],
					'client_num'=>$data['client_num'.$i],
					'stat_use'=>$start_used,
					'end_use'=>$data['new_num'.$i],
					'total_use'=>$data['toatal_water'.$i],
					'price_set_id'=>$data['price_setting_id'.$i],
					'new' => 1 ,

					'statust'=>'1',
					'create_date' =>$data['date_input_system'],
					'village_id'=>$data['village_1_ed'],
					'total_price'=>$data['total_price'.$i],

				);
				$this->_name= "tb_used";
				$this->insert($arr_item);
			}

		//	print_r($start_used);exit();
			//exit();
			$db->commit();
		}catch(Exception $e){
			$db->rollBack();
			Application_Model_DbTable_DbUserLog::writeMessageError($e);
			echo $e->getMessage();
		}
		 
	}
}

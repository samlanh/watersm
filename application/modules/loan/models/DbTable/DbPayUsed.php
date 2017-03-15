<?php

class Loan_Model_DbTable_DbPayUsed extends Zend_Db_Table_Abstract
{
	public function addDetailUsed($data){
		$db = $this->getAdapter();
		$db->beginTransaction();
		try{
	
			$identity_su = $data["record_row"];
			$idsu = explode(',', $identity_su);
	
			foreach ($idsu as $i){
	
	
				$arr_item =array(
						//'pu_id'				=>	$id,
						'client_id'				=>	$data['client_id'.$i],
						'stat_use'				=> $data['new_num'.$i],
						'total_use'				=> $data['toatal_water'.$i],
						'total_use'				=> $data['toatal_water'.$i],
				//     							'item_id'			=>	$data["item_id_".$i],
				//     							'food_id'			=>	$data["food_id_".$i],
	
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

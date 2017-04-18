<?php
class Report_Model_DbTable_DbReportUserPay extends Zend_Db_Table_Abstract
{
    protected  $db_name='tbl_payment';
    public function userGetMoney($search=null){
        $db=$this->getAdapter();
        $sql="
    SELECT SUM(pa.total_payment) AS total_usr_get_money,
	(SELECT usr.user_name FROM rms_users AS usr WHERE usr.id=pa.user_id LIMIT 1) AS user_name,
	(SELECT s.date_start FROM tb_settingprice AS s WHERE s.setId=pa.seting_price_id LIMIT 1) AS date_start,
	(SELECT s.date_stop FROM tb_settingprice AS s WHERE s.setId=pa.seting_price_id LIMIT 1) AS date_stop
    FROM  tbl_payment AS pa GROUP BY pa.user_id       
        ";
        return $db->fetchAll($sql);
    }
    public function getClientPayWithUser($search=null){
        try{
            $db = $this->getAdapter();
            $sql="
         SELECT 
	pay.`client_id`,
        (SELECT cl.client_number FROM `ln_client` AS cl WHERE cl.client_id=pay.`client_id` LIMIT 1) AS client_number,
        (SELECT cl.name_kh FROM `ln_client` AS cl WHERE cl.client_id=pay.`client_id` LIMIT 1) AS name_kh,
        (SELECT cl.sex FROM `ln_client` AS cl WHERE cl.client_id=pay.`client_id` LIMIT 1) AS sex,
        (SELECT v.village_name FROM `ln_village` AS v WHERE v.vill_id=pay.`village_id`) AS village,
	pay.`input_pay`,
	    (SELECT usr.user_name FROM `rms_users` AS usr WHERE pay.`user_id`=usr.id LIMIT 1) user_name,
	pay.`date_input`
 FROM`tbl_payment` AS pay
	
            ";

            if ((empty($search['adv_search']))
                AND (empty($search['village_name']))
                AND (empty($search['start_date']))
                AND (empty($search['end_date']))
                )
            {


                $where="
                 WHERE 
                    pay.`date_input`>=(SELECT s.`date_stop` FROM tb_settingprice AS s WHERE s.`status`=1 LIMIT 1) 
                AND 
                    pay.`date_input`<=(SELECT s.`earning_stop` FROM tb_settingprice AS s WHERE s.`status`=1 LIMIT 1)
                 ";
            }else{
                $where=" WHERE 1 ";
            }
            if(!empty($search['status']='')){
                $where.= " AND status = ".$search['status'];
               // print_r($search);exit();
            }
            if(!empty($search['search_option_number'])){
                $where.= " AND client_id = ".$search['search_option_number'];
            }
            if(!empty($search['village_name'])){
                $where.=" AND village_id= ".$search['village_name'];
            }
            if (!empty($search['user_id'])){
                $where.=" AND user_id= ".$search['user_id'];
            }
            if (!empty($search['start_date']) AND (!empty($search['end_date'])) ){
             //  print_r($search['start_date'].'='.$search['end_date']);exit();
                $where.=" AND date_input>= ".$search['start_date']."  AND date_input<= ".$search['end_date'];
                print_r($where);exit();

            }
            $order="  ORDER BY date_input ASC ";
            return $db->fetchAll($sql.$where.$order);
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

}
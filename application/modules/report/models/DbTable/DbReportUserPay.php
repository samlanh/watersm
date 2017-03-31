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

}
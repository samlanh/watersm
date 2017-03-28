<?php

/**
 * Created by PhpStorm.
 * User: BUNCHHEANG
 * Date: 14-Mar-17
 * Time: 1:43 PM
 */

class Learning_Model_DbTable_DBLearning extends Zend_Db_Table_Abstract
{

        protected $_name='tbl_category';
        public function addCategory($data){
                $db=$this->getAdapter();
                $db->beginTransaction();
                try{
                        $_array=array(
                            'category'=>$data['txt_category'],
                            'description'=>$data['txtDesc']
                        );
                        $this->_name='tbl_category';
                        $this->insert($_array);
                        $db->commit();

                }catch (Exception $e){
                        Application_Form_FrmMessage::message("Application error somlanh");
                        Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
                }

        }
        public function getCategory($search){
                $db=$this->getAdapter();
                $spl="SELECT * FROM tbl_category ORDER BY cat_id DESC";

                
                return $db->fetchAll($spl);

        }
        public function updateData($data){
                $this->getAdapter();
                $array=array(

                        'category'=>$data['txt_category'],
                        'description'=>$data['txtDesc']
                );
                $where='cat_id='.$data['cat_id_text'];


                $this->update($array,$where);


        }
       
        function getDatabyId($id){
                $db = $this->getAdapter();
                $spl="SELECT * FROM tbl_category where cat_id = $id";
                return $db->fetchRow($spl);
        }
    public function deletecategory($id){
            $where='cat_id='.$id;
            $this->delete($where);
    }

}

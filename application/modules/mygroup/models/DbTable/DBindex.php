<?php

class Mygroup_Model_DbTable_DBindex extends Zend_Db_Table_Abstract
{
    protected $_name = 'tb_catagory';
    public function getUserId(){
    	$session_user=new Zend_Session_Namespace('auth');
    	return $session_user->user_id;
    }
    public function init()
    {
    	$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
    	$this->tvalidate = 'dijit.form.ValidationTextBox';
    	$this->filter = 'dijit.form.FilteringSelect';
    	$this->text = 'dijit.form.TextBox';
    	$this->tarea = 'dijit.form.SimpleTextarea';
    }
    function addcatagory($data){
    	$db =$this->getAdapter();
    	$db->beginTransaction();
    	try{
    		$array=array(
    				'catagory'=>$data['catagory'],
    				'decription'=>$data['decription']
    		);
    		
    	$this->_name='tb_catagory';
    	$this->insert($array);
    	$db->commit();
    	}catch (Exception $e){echo 'can add data '; }
    	
    }
    function getallcatagory(/* $search=NULL */){
    	$db=$this->getAdapter();
    	$sql="SELECT * FROM tb_catagory as c ORDER BY c.cat_id DESC";
    	return $db->fetchAll($sql); 
    	
    }
    public function getCatagoryByid($id){
    	$db = $this->getAdapter();
    	$sql = "SELECT * FROM tb_catagory AS t WHERE t.`cat_id`=".$id;
    	return $db->fetchRow($sql);
    	//print_r($sql);exit();
    }
	 function updatecatagory($data){
	    	$db =$this->getAdapter();
	    	$db->beginTransaction();
	    	try{
	    		$where="cat_id=".$data['id'];
	    		$array = array(
	    			
	    				'catagory'=>$data['catagory'],
	    				'decription'=>$data['decription']
	    		);
	    		
	    	$this->_name='tb_catagory';
	    	//$this->update($array, $where);
	    	$this->update($array, $where);
	    	$db->commit();
	    	}catch (Exception $e){echo 'can add data '; 
		    	Application_Form_FrmMessage::message("Application Error");
		    	Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		    	$db->rollBack();
	    	}
	    }  
   	    
}


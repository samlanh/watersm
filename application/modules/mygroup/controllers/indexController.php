<?php
class Mygroup_indexController extends Zend_Controller_Action {
	const REDIRECT_URL='/mygroup';
	protected $tr;
	public function init()
	{
		$this->tr=Application_Form_FrmLanguages::getCurrentlanguage();
		/* Initialize action controller here */
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
	public function indexAction(){
		try{
			$db=new Mygroup_Model_DbTable_DBindex();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => '');
						
			}
// 			$rs_rows= $db->geteAllSettingprice($search);
// 			$glClass = new Application_Model_GlobalClass();
// 			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
// 			$list = new Application_Form_Frmtable();
			
			$list = new Application_Form_Frmtable();
			$rs_row=$db->getallcatagory();
			$collumns=array("catagory","decription");
			
			$link=array(
					'module'=>'mygroup','controller'=>'index','action'=>'edit',
			);
			$this->view->myview=$list->getCheckList(0, $collumns,$rs_row,array('catagory'=>$link,'decription'=>$link));
		}catch (Exception $e){

			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
				
		}
		$frm = new Mygroup_Form_FrmIndex();
		$frm ->FrmIndex();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_index = $frm;
	}
	
	
	
	
	public  function addAction(){

		if($this->getRequest()->isPost()){
   		$data = $this->getRequest()->getPost();
   		$db = new Mygroup_Model_DbTable_DBindex();
   		 
   		try{
   				if(!empty($data['save_new'])){
					Application_Form_FrmMessage::message('ការ​បញ្ចូល​​ជោគ​ជ័យ');
   					$db->addcatagory($data);
				}else{
					Application_Form_FrmMessage::Sucessfull('ការ​បញ្ចូល​​ជោគ​ជ័យ', self::REDIRECT_URL . '/mygroup/index');
					$db->addcatagory($data);
				}
	   		}catch(Exception $e){
	   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
	   			$err =$e->getMessage();
	   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
	   		}
   		}
		
		
		$frm = new Mygroup_Form_FrmIndex();
		$frm ->FrmIndex();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_index = $frm;
	}
	
	function editAction(){
		$id = $this->getRequest()->getParam('id');
		$db_ca = new Mygroup_Model_DbTable_DBindex();
		$rows=$db_ca->getCatagoryByid($id);
		
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			 
			try{
				$_data['id']= $id;
				
				$db_ca-> updatecatagory($_data);
				Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !",'/mygroup/index');
			}catch(Exception $e){
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		
		$frm = new Mygroup_Form_FrmIndex();
		$frm ->FrmIndex($rows);
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_index = $frm;
	}
	
}


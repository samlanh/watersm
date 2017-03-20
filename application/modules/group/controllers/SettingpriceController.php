<?php
class Group_SettingpriceController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL = '/group';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
	public function indexAction(){
		  try{
			$db = new Group_Model_DbTable_DbSettingprice();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => '',
						'status_search' => -1,
						'Datesearch_start' => date('Y-m-d'),
						'Datesearch_stop' => date('Y-m-d')
						
						);
				
			}
			$rs_rows= $db->geteAllSettingprice($search);
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("PRICE","SERVICE_PRICE","Date_start","Date_stop","DEADLINE","NOTE","STATUS","USEBY");
			$link=array(
					'module'=>'group','controller'=>'settingprice','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns,$rs_rows,array('price'=>$link,'note'=>$link,'service_price'=>$link,'status'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		$frm = new Group_Form_Frmsettingprice();
		$frm_pro=$frm->Frmsettingprice();
		Application_Model_Decorator::removeAllDecorator($frm_pro);
		$this->view->frm_setting_price = $frm_pro; 
	 
	}
	
	
   function addAction(){
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		$db = new Group_Model_DbTable_DbSettingprice();
   		 
   		try{
   			$db->addsetting($_data);
   				if(!empty($_data['save_new'])){
					Application_Form_FrmMessage::message('ការ​បញ្ចូល​​ជោគ​ជ័យ');
				}else{
					Application_Form_FrmMessage::Sucessfull('ការ​បញ្ចូល​​ជោគ​ជ័យ', self::REDIRECT_URL . '/settingprice/index');
				}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	
   	$frm = new Group_Form_Frmsettingprice();
   	$frm_pro=$frm->Frmsettingprice();
   	Application_Model_Decorator::removeAllDecorator($frm_pro);
   	$this->view->frm_setting_price = $frm_pro;
   }
   
   function editAction(){
   	 $id = $this->getRequest()->getParam("id");
   	$db_co = new Group_Model_DbTable_DbSettingprice();
   	$row = $db_co->getSettingpriceById($id);
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		
   		try{
   			$_data['id']= $id;
   			/* print_r($_data);exit(); */
   			$db_co-> updatesetting($_data);
   			Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !",'/group/settingprice');
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	} 
   	
   	$frm = new Group_Form_Frmsettingprice();
   	$frm_pro=$frm->Frmsettingprice($row);
   	Application_Model_Decorator::removeAllDecorator($frm_pro);
   	$this->view->frm_setting_price = $frm_pro;
   
   }
   public  function addPropertytypeAction(){ // ajax from land controllert
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		$_db = new Group_Model_DbTable_DbSettingprice();
   		$id = $_db->ajaxPropertytype($_data);
   		//print_r(Zend_Json::encode($id));
   		//exit();
   	}
   }
}


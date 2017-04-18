<?php
class Group_ExpenController extends Zend_Controller_Action {
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
			$db = new Group_Model_DbTable_Dbexpen();
			if($this->getRequest()->isPost()){
				$search=$this->getRequest()->getPost();
			}
			else{
				$search = array(
						'adv_search' => ''
						);
				
			}
			$rs_rows= $db->geteAllexpen($search);
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = 
      array("PAYFOR","UNITPRICE","PRICE","TOTAL_MONNEY","PAID_DAY","NOTE","ALL_STATUS","VILLAGE","USEBY");

			$link=array(
					'module'=>'group','controller'=>'expen','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0,$collumns,$rs_rows,array('price'=>$link,'unit'=>$link,'total_price'=>$link,'expen_title'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}
		
		$frm = new Group_Form_Frmexpen();
      $frm_expen=$frm->frmexpend();
      Application_Model_Decorator::removeAllDecorator($frm_expen);
      $this->view->frm_expen = $frm_expen;
	 
	}
	
	
   function addAction(){
   	 if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		$db = new Group_Model_DbTable_Dbexpen();
   		 
   		try{
   			$db->addexpan($_data);
   				if(!empty($_data['save_new'])){
					Application_Form_FrmMessage::message('ការ​បញ្ចូល​​ជោគ​ជ័យ');
				}else{
					Application_Form_FrmMessage::Sucessfull('ការ​បញ្ចូល​​ជោគ​ជ័យ', self::REDIRECT_URL . '/expen/index');
				}
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	}
   	
   	$frm = new Group_Form_Frmexpen();
   	$frm_expen=$frm->frmexpend();
   	Application_Model_Decorator::removeAllDecorator($frm_expen);
   	$this->view->frm_expen = $frm_expen; 
   }
   
   function editAction(){
   	 $id = $this->getRequest()->getParam("id");
   	$db_co = new Group_Model_DbTable_Dbexpen();
   	$row = $db_co->getExpenByID($id);
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		try{
   			$_data['id']= $id;
   			//print_r($_data);exit(); 
   			$db_co-> updateexpen($_data);
   			Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !",'/group/expen');
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	} 
   
      $frm = new Group_Form_Frmexpen();
      $frm_expen=$frm->frmexpend($row);
      Application_Model_Decorator::removeAllDecorator($frm_expen);
      $this->view->frm_expen = $frm_expen;
   
   }
  
}


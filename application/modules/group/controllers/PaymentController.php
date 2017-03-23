<?php
class Group_PaymentController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL = '/group';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
	public function indexAction(){
	   	$db = new Group_Model_DbTable_DbPayment();
	   	if($this->getRequest()->isPost()){
	   		$search=$this->getRequest()->getPost();
	   	}
	   	else{
	   		$search = array(
	   				"code"=>'',
	   				//"currency_type"=>-1,
	   				//"status"=>-1,
	   				//'start_date'=> date('Y-m-d'),
	   				//'end_date'=>date('Y-m-d'),
	   		);
	   	}
		$rs_rows= $db->geteAllpayment($search);//call frome model
		$this->view->rows =$db->geteAllpayment($search);
	   	$frm = new Group_Form_FrmPayment();
	   	$frm_pay = $frm->FrmPayment();
	   	Application_Model_Decorator::removeAllDecorator($frm);
	 	$this->view->frm_pay = $frm_pay;
	 
	}
	
	
   function addAction(){
   	$frm = new Group_Form_FrmPayment();
   	$frm_pay=$frm->FrmPayment();
   	Application_Model_Decorator::removeAllDecorator($frm_pay);
   	$this->view->frm_pay = $frm_pay;
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
   
  	function  indexoldAction(){
  		
  		try{
  			$db = new Group_Model_DbTable_DbPayment();
  			if($this->getRequest()->isPost()){
  				$search=$this->getRequest()->getPost();
  			}
  			else{
  				$search = array(
  						'adv_search' => '',
  						//'status_search' => -1,
  						//'Datesearch_start' => date('Y-m-d'),
  						//'Datesearch_stop' => date('Y-m-d')
  		
  				);
  		
  			}
  			$rs_rows= $db->geteAllpayment($search);
  			$glClass = new Application_Model_GlobalClass();
  			//$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
  			$list = new Application_Form_Frmtable();
  			$collumns = array("PRICE","SERVICE_PRICE","Date_start","Date_stop","DEADLINE","NOTE","STATUS","USEBY");
  			$link=array(
  					'module'=>'group','controller'=>'payment','action'=>'edit',
  			);
  			$this->view->list=$list->getCheckList(0, $collumns,$rs_rows,array('total_use'=>$link));
  		}catch (Exception $e){
  			Application_Form_FrmMessage::message("Application Error");
  			echo $e->getMessage();
  			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
  		}
  		
  		$frm = new Group_Form_FrmPayment();
  		$frm_pro=$frm->FrmPayment();
  		Application_Model_Decorator::removeAllDecorator($frm_pro);
  		$this->view->frm_pay = $frm_pro;
  	}
   
   

}


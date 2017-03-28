<?php
class Payment_PaymentController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL = '/payment';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
	public function indexAction(){
		//$id = $this->getRequest()->getParam("code");
	   	$db = new Payment_Model_DbTable_DbPayment();

	   	if($this->getRequest()->isPost()){
			$search='';
	   		$searchdata=$this->getRequest()->getPost();
			if (isset($searchdata['search'])){

				$search=array(
					'code_search'=>$searchdata['code_search']
				);
			}
			if (isset($searchdata['save_new'])){
				print_r($searchdata);exit();
				$db->addPayment($searchdata);
				echo "<script>alert('Save new');</script>";
			}
			if (isset($searchdata['save_close'])){
				print_r($searchdata);exit();
				$db->addPayment($searchdata);
				Application_Form_FrmMessage::redirectUrl("/payment/index");
				echo "<script>alert('Save close')</script>";
			}


	   	}else{
			$search=array(
				'code_search'=>''
			);
			
		}
		$rs_row=$db->get_client_pay($search);

	//	$rs_rows= $db->geteAllpayment($searchtada;//call frome model
		//$this->view->rows =$db->geteAllpayment($searchdata);


		$frm = new Payment_Form_FrmPayment();
		$frm_pay = $frm->FrmPayment($rs_row);
	   	Application_Model_Decorator::removeAllDecorator($frm);
	 	$this->view->frm_pay = $frm_pay;
	 
	}
	
	
   function addAction(){
   	$frm = new Payment_Form_FrmPayment();
   	$frm_pay=$frm->FrmPayment();
   	Application_Model_Decorator::removeAllDecorator($frm_pay);
   	$this->view->frm_pay = $frm_pay;
   }

   function editAction(){
   	 $id = $this->getRequest()->getParam("id");
   	$db_co = new Payment_Model_DbTable_DbSettingprice();
   	$row = $db_co->getSettingpriceById($id);
   	if($this->getRequest()->isPost()){
   		$_data = $this->getRequest()->getPost();
   		
   		try{
   			$_data['id']= $id;
   			/* print_r($_data);exit(); */
   			$db_co-> updatesetting($_data);
   			Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !",'/payment/settingprice');
   		}catch(Exception $e){
   			Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
   			$err =$e->getMessage();
   			Application_Model_DbTable_DbUserLog::writeMessageError($err);
   		}
   	} 
   	
   	$frm = new Payment_Form_Frmsettingprice();
   	$frm_pro=$frm->Frmsettingprice($row);
   	Application_Model_Decorator::removeAllDecorator($frm_pro);
   	$this->view->frm_setting_price = $frm_pro;
   
   }
   function  getClientSearchAction(){
	   if($this->getRequest()->isPost()){
		   $data = $this->getRequest()->getPost();
		   $db = new Payment_Model_DbTable_DbPayment();
		   $dataclient=$db->getVillageByAjax($data['vllage']);
		   print_r(Zend_Json::encode($dataclient));
		   exit();
	   }
	
}

	function getClientNumAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Payment_Model_DbTable_DbPayment();
			$search_option_numbert=$db->getNumerClient($data['search_option_number']);
			print_r(Zend_Json::encode($search_option_numbert));
			exit();
		}
}

}


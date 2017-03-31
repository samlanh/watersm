<?php
class Payment_indexController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
	const REDIRECT_URL = '/payment';
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	
	
	public function indexAction(){

		try{
			$db = new Payment_Model_DbTable_DbPayment();
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
			$rs_rows= $db->getListPayment($search);

			$list = new Application_Form_Frmtable();
			$collumns = array("លេខកូដ","ឈ្មោះ","ប្រាក់ដែលបានបង់","ប្រាក់បង់សរុប","ប្រាក់បង់ប្រចាំខែ","បំណុលចាស់","បំណុលថ្មី","ភូមិ","ថ្ងៃបញ្ជូល","អ្នកបញ្ជូល");
			$link=array(
				'module'=>'payment','controller'=>'index','action'=>'edit',
			);
			$this->view->list=$list->getCheckList(0, $collumns,$rs_rows,array('name_kh'=>$link,'client_num'=>$link,'total_payment'=>$link));
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}

		$frm = new Payment_Form_FrmPayment();
		$frm_pay=$frm->FrmPayment();
		Application_Model_Decorator::removeAllDecorator($frm_pay);
		$this->view->frm_pay = $frm_pay;

	}
   function addAction(){

	   //$id = $this->getRequest()->getParam("code");
	   $db = new Payment_Model_DbTable_DbPayment();

	   if($this->getRequest()->isPost()){
		   $search='';
		   $searchdata=$this->getRequest()->getPost();
		   if (isset($searchdata['search'])){
			   print_r($searchdata);exit();
			   $search=array(
				   'code_search'=>$searchdata['code_search']
			   );
		   }
		   if (isset($searchdata['save_new'])){
			   //print_r($searchdata);exit();
			   $db->addPayment($searchdata);
			   echo "<script>alert('Save new');</script>";
		   }
		   if (isset($searchdata['save_close'])){
			//   print_r($searchdata);exit();
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
	   $frm = new Payment_Form_FrmPayment();
	   $frm_pay = $frm->FrmPayment($rs_row);
	   Application_Model_Decorator::removeAllDecorator($frm);
	   $this->view->frm_pay = $frm_pay;

   }

	function editAction(){
		$id = $this->getRequest()->getParam("id");
		$db_co = new Payment_Model_DbTable_DbPayment();
		$row = $db_co->getPaymentById($id);
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();

			try{
				$_data['id']= $id;
				$db_co->updatePayment($_data);
				Application_Form_FrmMessage::Sucessfull("ការ​បញ្ចូល​ជោគ​ជ័យ !",'/payment/index');
			}catch(Exception $e){
				Application_Form_FrmMessage::message("ការ​បញ្ចូល​មិន​ជោគ​ជ័យ");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}

		$frm = new Payment_Form_FrmPayment();
		$frm_pay=$frm->FrmPayment($row);
		Application_Model_Decorator::removeAllDecorator($frm_pay);
		$this->view->frm_pay = $frm_pay;
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


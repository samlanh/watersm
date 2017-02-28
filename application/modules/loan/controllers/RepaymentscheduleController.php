<?php
class Loan_RepaymentScheduleController extends Zend_Controller_Action {
	private $activelist = array('មិនប្រើ​ប្រាស់', 'ប្រើ​ប្រាស់');
    public function init()
    {    	
     /* Initialize action controller here */
    	header('content-type: text/html; charset=utf8');
    	defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}
	private $sex=array(1=>'M',2=>'F');
	public function indexAction(){
		try{
		    if($this->getRequest()->isPost()){
 				$search = $this->getRequest()->getPost();
 			}else{
				$search = array(
					    'txt_search'=>'',
						'client_name'=> -1,
						'schedule_opt' => -1,
						'branch_id' => -1,
						'status' => -1,
						'start_date'=> date('Y-m-d'),
						'end_date'=>date('Y-m-d'),
						 );
			}
			$db = new Loan_Model_DbTable_DbRepaymentSchedule();
			$rs_rows= $db->getAllReschedule($search,1);
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("BRANCH_NAME","SALE_NO","CUSTOMER_NAME","PROPERTY_NAME","ប្រភេទបង់","BALANCE","ប្រភេទបង់","BALANCE","DATE_BUY",
				"STATUS");
			$link=array(
					'module'=>'loan','controller'=>'repaymentschedule','action'=>'view',
			);
			$link_info=array('module'=>'loan','controller'=>'repaymentschedule','action'=>'edit',);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('branch_name'=>$link_info,'sale_number'=>$link_info,'name_kh'=>$link_info,'land_address'=>$link_info,'total_capital'=>$link_info),0);
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			echo $e->getMessage();exit();
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}	
		$frm = new Loan_Form_FrmSearchLoan();
		$frm = $frm->AdvanceSearch();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
  }
  function addAction()
  {
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Loan_Model_DbTable_DbRepaymentSchedule();
				$_dbmodel->addRepayMentSchedule($_data);
				if(!empty($_data['saveclose'])){
					Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/loan/repaymentschedule");
				}else{
					Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				$err =$e->getMessage();
				Application_Model_DbTable_DbUserLog::writeMessageError($err);
			}
		}
		$frm = new Loan_Form_FrmLoan();
		$frm_loan=$frm->FrmAddLoan();
		Application_Model_Decorator::removeAllDecorator($frm_loan);
		$this->view->frm_loan = $frm_loan;
        $db = new Application_Model_DbTable_DbGlobal();
        $this->view->allclient = $db->getAllClient();
        $this->view->allclient_number = $db->getAllClientNumber();
        $frmpopup = new Application_Form_FrmPopupGlobal();
//         $this->view->frmpupoploantype = $frmpopup->frmPopupLoanTye();
//         $this->view->frmPopupZone = $frmpopup->frmPopupZone();
        
        $db_keycode = new Application_Model_DbTable_DbKeycode();
        $this->view->keycode = $db_keycode->getKeyCodeMiniInv();        
//         $this->view->graiceperiod = $db_keycode->getSystemSetting(9);
		$db = new Setting_Model_DbTable_DbLabel();
		$this->view->setting=$db->getAllSystemSetting();
		
		$key = new Application_Model_DbTable_DbKeycode();
		$this->view->data=$key->getKeyCodeMiniInv(TRUE);
		
		$id = $this->getRequest()->getParam('id');
		if(!empty($id)){
		$db = new Loan_Model_DbTable_DbLandpayment();
		$this->view->rsresult =  $db->getTranLoanByIdWithBranch($id,null);
		}
		$this->view->id = $id;
	}	
// 	public function addloanAction(){
// 		if($this->getRequest()->isPost()){
// 			$data=$this->getRequest()->getPost();
// 			$db = new Loan_Model_DbTable_DbRepaymentSchedule();
// 			$id = $db->addNewLoanGroup($data);
// 			$suc = array('sms'=>'ប្រាក់ឥណទានត្រូវបានបញ្ចូលដោយជោគជ័យ !');
// 			print_r(Zend_Json::encode($suc));
// 			exit();
// 		}
// 	}
	public function viewAction(){
		// 		$this->_helper->layout()->disableLayout();
		$id = $this->getRequest()->getParam('id');
		$db_g = new Application_Model_DbTable_DbGlobal();
		if(empty($id)){
			Application_Form_FrmMessage::Sucessfull("RECORD_NOT_FUND","/loan/index/index");
		}
		$db = new Loan_Model_DbTable_DbLoanIL();
		$row = $db->getLoanviewById($id);
		$this->view->tran_rs = $row;
	}
	
	public function editAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try{
				$_dbmodel = new Loan_Model_DbTable_DbRepaymentSchedule();
				$_dbmodel->updateRepaymentSchedule($_data);
				Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/loan/repaymentschedule/index");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($err =$e->getMessage());
			}
		}
		$id = $this->getRequest()->getParam('id');
		$_db = new Loan_Model_DbTable_DbRepaymentSchedule();
		$data_row = $_db->getRescheduleById($id); 
		$this->view->rsresult = $data_row;
// 		$rs = $db_g->getLoanFundExist($id);
// 		if($rs==true){
// 			Application_Form_FrmMessage::Sucessfull("LOAN_FUND_EXIST","/loan/repaymentschedule/index");
// 		}
// 		$db = new Loan_Model_DbTable_DbLoanIL();
// 		$row = $db->getTranLoanByIdWithBranch($id,1,1);
// 		if(empty($row)){ Application_Form_FrmMessage::Sucessfull("RECORD_NOT_EXIST","/loan/repaymentschedule/index"); }
		print_r($data_row);
		$frm = new Loan_Form_FrmRepaymentSchedule();
		$frm_loan=$frm->FrmAddLoan($data_row);
		Application_Model_Decorator::removeAllDecorator($frm_loan);
		$this->view->frm_loan = $frm_loan;
        $db = new Application_Model_DbTable_DbGlobal();
//      $this->view->allclient = $db->getAllClient();
//      $this->view->allclient_number = $db->getAllClientNumber();
//         $frmpopup = new Application_Form_FrmPopupGlobal();
//      $this->view->frmpupoploantype = $frmpopup->frmPopupLoanTye();
//      $this->view->frmPopupZone = $frmpopup->frmPopupZone();
        $db_keycode = new Application_Model_DbTable_DbKeycode();
        $this->view->keycode = $db_keycode->getKeyCodeMiniInv();
//         $this->view->graiceperiod = $db_keycode->getSystemSetting(9);
		$db = new Setting_Model_DbTable_DbLabel();
		$this->view->setting=$db->getAllSystemSetting();
	}
	
	function getloanRescheduleAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db=new Loan_Model_DbTable_DbRepaymentSchedule();
			$row=$db->getLaoForRepaymentSchedule($data['member_id']);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
}


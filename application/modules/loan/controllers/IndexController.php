<?php
class Loan_IndexController extends Zend_Controller_Action {
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
 			}
			else{
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
			
			$db = new Loan_Model_DbTable_DbLandpayment();
			$rs_rows= $db->getAllIndividuleLoan($search);
			$glClass = new Application_Model_GlobalClass();
			$rs_rows = $glClass->getImgActive($rs_rows, BASE_URL, true);
			$list = new Application_Form_Frmtable();
			$collumns = array("BRANCH_NAME","SALE_NO","CUSTOMER_NAME","HOUSE_NO","STREET","ប្រភេទបង់","TOTAL_SOLD","DISCOUNT_PERCENT","DISCOUNT","PAID","BALANCE","DATE_BUY",
				"STATUS","បង់ប្រាក់","ចេញតារាង","កិច្ចសន្យា");
			$link_info=array('module'=>'loan','controller'=>'index','action'=>'edit',);
			$agreement=array('module'=>'report','controller'=>'paramater','action'=>'rpt-agreement',);
			$reschedule=array('module'=>'loan','controller'=>'repaymentschedule','action'=>'add',);
			$payment=array('module'=>'loan','controller'=>'ilpayment','action'=>'add',);
			$this->view->list=$list->getCheckList(0, $collumns, $rs_rows,array('បង់ប្រាក់'=>$payment,'ចេញតារាង'=>$reschedule,'កិច្ចសន្យា'=>$agreement,'name_kh'=>$link_info,'land_address'=>$link_info,'client_number'=>$link_info,'name_en'=>$link_info,'branch_name'=>$link_info,'sale_number'=>$link_info),0);
		}catch (Exception $e){
			Application_Form_FrmMessage::message("Application Error");
			Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
		}	
		$frm_search = new Loan_Form_FrmSearchLoan();
		$frm = $frm_search->AdvanceSearch();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frm_search = $frm;
		
		//$db = new Loan_Model_DbTable_DbLandpayment();
		//$db->updateEnddateSale();
  }
  function addAction()
  {
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try {
				$_dbmodel = new Loan_Model_DbTable_DbLandpayment();
				$_dbmodel->addSchedulePayment($_data);
				if(!empty($_data['saveclose'])){
					//Application_Form_FrmMessage::Sucessfull("INSERT_SUCCESS","/loan");
					
				}else{
					//Application_Form_FrmMessage::message("INSERT_SUCCESS");
				}
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($e->getMessage());
			}
		}
		$frm = new Loan_Form_FrmLoan();
		$frm_loan=$frm->FrmAddLoan();
		Application_Model_Decorator::removeAllDecorator($frm_loan);
		$this->view->frm_loan = $frm_loan;
		
		$frmpopup = new Application_Form_FrmPopupGlobal();
		$db = new Application_Model_DbTable_DbGlobal();
		$co_name = $db->getAllCoNameOnly();
		array_unshift($co_name,array(
		        'id' => -1,
		        'name' => '---Add New ---',
		) );
	    $this->view->co_name=$co_name;
	    
	    $db = new Application_Model_DbTable_DbGlobal();
	    $this->view->client_doc_type = $db->getclientdtype();
	    
	    $key = new Application_Model_DbTable_DbKeycode();
		$this->view->data=$key->getKeyCodeMiniInv(TRUE);
	}	
	public function editAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			try{
				$_dbmodel = new Loan_Model_DbTable_DbLandpayment();
				$_dbmodel->updateLoanById($_data);
				Application_Form_FrmMessage::Sucessfull("UPDATE_SUCCESS","/loan/index/index");
			}catch (Exception $e) {
				Application_Form_FrmMessage::message("INSERT_FAIL");
				Application_Model_DbTable_DbUserLog::writeMessageError($err =$e->getMessage());
			}
		}
		$id = $this->getRequest()->getParam('id');
		$db = new Loan_Model_DbTable_DbLandpayment();
		$rs = $db->getSalePaidExist($id,null);

		if(count($rs)>=2){
			Application_Form_FrmMessage::Sucessfull("ទិន្នន័យនេះមានប្រវត្តបង់ច្រើនដងរួចហើយ មិនអាចកែប្រែបានទេ","/loan/index/index");
		}
		
		$row = $db->getTranLoanByIdWithBranch($id,null);
		if(empty($row)){Application_Form_FrmMessage::Sucessfull("RECORD_NOTFUND","/loan/index");}
		$rs = array();
		if($row['payment_id']==6 OR $row['payment_id']==4){
			$rs = $db->getSaleScheduleById($id,$row['payment_id']);
		}
		$this->view->rs = $rs;
		$frm = new Loan_Form_FrmLoan();
		$frm_loan=$frm->FrmAddLoan($row);
		Application_Model_Decorator::removeAllDecorator($frm_loan);
		$this->view->frm_loan = $frm_loan;
		$this->view->datarow = $row;
		$this->view->amount_price = $row['balance']+$row['paid_amount']-$row['other_fee'];
		//echo $row['balance']+$row['paid_amount']-$row['other_fee'];
		//echo $row['price_sold']-($row['price_sold']*$row['discount_percent'])-$row['discount_amount'];
	
		$db = new Application_Model_DbTable_DbGlobal();
		$this->view->client_code=array();//$dataclient;
			
		$this->view->client_name=array();
		
		$co_name = $db->getAllCoNameOnly();
		array_unshift($co_name,array(
				'id' => -1,
				'name' => '---Add New ---',
		) );
		$this->view->co_name=$co_name;
		
		$key = new Application_Model_DbTable_DbKeycode();
		$this->view->data=$key->getKeyCodeMiniInv(TRUE);
	}
	public function addloanAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db = new Loan_Model_DbTable_DbLoan();
			$id = $db->addNewLoanGroup($data);
			$suc = array('sms'=>'ប្រាក់ឥណទានត្រូវបានបញ្ចូលដោយជោគជ័យ !');
			print_r(Zend_Json::encode($suc));
			exit();
		}
	}
	public function viewAction(){
// 		$this->_helper->layout()->disableLayout();
		$id = $this->getRequest()->getParam('id');
		$db_g = new Application_Model_DbTable_DbGlobal();
		if(empty($id)){
			Application_Form_FrmMessage::Sucessfull("RECORD_NOT_FUND","/loan/index/index");
		}
		$db = new Loan_Model_DbTable_DbLandpayment();
		$row = $db->getLoanviewById($id);
		$this->view->tran_rs = $row;
	}
	function getLoanlevelAction(){
		if($this->getRequest()->isPost()){
				$data = $this->getRequest()->getPost();
				$db = new Loan_Model_DbTable_DbLoanIL();
				$row = $db->getLoanLevelByClient($data['client_id'],$data['type']);
				print_r(Zend_Json::encode($row));
			    exit();
		}
	}
	public function getLoaninfoAction(){//from repayment schedule
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db=new Loan_Model_DbTable_DbRepaymentSchedule();
			$row=$db->getLoanInfo($data['loan_id']);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getloanBymemberidAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db=new Loan_Model_DbTable_DbRepaymentSchedule();
			$row=$db->getLoanInfoById($data['sale_id']);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getsaleinfoAction(){
		if($this->getRequest()->isPost()){
			$data=$this->getRequest()->getPost();
			$db=new Loan_Model_DbTable_DbRepaymentSchedule();
			$row=$db->getSaleInfoById($data['sale_id']);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
	function getalllandAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Application_Model_DbTable_DbGlobal();
			$action = (!empty($data['action'])?$data['action']:null);
			$row = $db->getAllLandInfo($data['branch_id'],1,$action);
			print_r(Zend_Json::encode($row));
			exit();
		}
	}
    function getloannumberAction(){
    			if($this->getRequest()->isPost()){
    				$data = $this->getRequest()->getPost();
    				$db = new Application_Model_DbTable_DbGlobal();
		            $loan_number = $db->getLoanNumber($data);
    				print_r(Zend_Json::encode($loan_number));
    				exit();
    			}
    }
    function getReceiptNumberAction(){
    	if($this->getRequest()->isPost()){
    		$data = $this->getRequest()->getPost();
    		$db = new Application_Model_DbTable_DbGlobal();
    		$loan_number = $db->getReceiptByBranch($data);
    		print_r(Zend_Json::encode($loan_number));
    		exit();
    	}
    }
	function addschedultestAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
				$_dbmodel = new Loan_Model_DbTable_DbLandpayment();
				$rows_return=$_dbmodel->addScheduleTestPayment($_data);
				print_r(Zend_Json::encode($rows_return));
				exit();
		}
	}
	function demoscheduleAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Loan_Model_DbTable_DbLandpayment();
			$rows_return=$_dbmodel->demoSchedule($_data);
			print_r(Zend_Json::encode($rows_return));
			exit();
		}
	}
	 
	function previewreschedulAction(){
		if($this->getRequest()->isPost()){
			$_data = $this->getRequest()->getPost();
			$_dbmodel = new Loan_Model_DbTable_DbLandpayment();
			//if($_data['old_paymentmethod']){$_data['deposit'] = $_data['deposit']+$_data['paid_before'];}
			$rows_return=$_dbmodel->addScheduleTestPayment($_data);
			print_r(Zend_Json::encode($rows_return));
			exit();
		}
	}
	function addNewloantypeAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$data['status']=1;
			$data['display_by']=1;
			$db = new Other_Model_DbTable_DbLoanType();
			$id = $db->addViewType($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function addStaffAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Loan_Model_DbTable_DbLandpayment();
			$id = $db->addStaff($data);
			print_r(Zend_Json::encode($id));
			exit();
		}
	}
	function addClientAction(){
		if($this->getRequest()->isPost()){
			$data = $this->getRequest()->getPost();
			$db = new Group_Model_DbTable_Dbindexused();
			$id = $db->addClientused($data);
			print_r(Zend_Json::encode($id));
			exit();
			
		}
	}
}


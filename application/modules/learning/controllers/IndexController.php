<?php
class Learning_indexController extends Zend_Controller_Action {
	const REDIRECT_URL = '/learning/index';
	public function init()
	{
		header('content-type: text/html; charset=utf8');
		defined('BASE_URL')	|| define('BASE_URL', Zend_Controller_Front::getInstance()->getBaseUrl());
	}

	public function indexAction(){

		$db =new Learning_Model_DbTable_DBLearning();
		try{
			if($this->getRequest()->isPost()) {
				$search = $this->getRequest()->getPost();
				echo "click";
			}else{
				$search=array(
					'search'=>''
				);
			}
			$this->view->rows =$db->getCategory($search);

		}catch (Exception $e){

		}


		
		$frm=new Learning_Form_FrmLearning();
		$frm->FrmLearning();
		$this->view->frmCategory=$frm;

	}

	public function editAction(){
		$db = new Learning_Model_DbTable_DBLearning();
		if ($this->getRequest()->isPost()){

			$data=$this->getRequest()->getPost();
			$this->view->rows=$db->updateData($data);
			$this->_redirect("/learning/index/");
		}

		$id = $this->getRequest()->getParam("id");

		$this->view->rows = $db->getDatabyId($id);


	}
	





	public function addAction(){
		$db=new Learning_Model_DbTable_DBLearning();

		if($this->getRequest()->isPost()){

			$data = $this->getRequest()->getPost();


			try{
				if (!empty($data)){
				$db->addCategory($data);

				}
				$this->_redirect('/learning/index');
			

			}catch (Exception $e){
				Application_Form_FrmMessage::message("Save fail");
				Application_Form_FrmMessage::redirectUrl('learning/index');
			}
		}

		$frm=new Learning_Form_FrmLearning();
		$frm->FrmLearning();
		Application_Model_Decorator::removeAllDecorator($frm);
		$this->view->frmCategory=$frm;
		

	}
	public  function deleteAction(){
		$data=$this->getRequest()->getParam('id');
		$db=new Learning_Model_DbTable_DBLearning();
		$db->deletecategory($data);
		$this->_redirect("/learning/index");
	}
}

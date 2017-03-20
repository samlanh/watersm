<?php 
Class Group_Form_FrmPayment extends Zend_Dojo_Form {
	protected $tr=null;
	protected $tvalidate=null ;//text validate
	protected $filter=null;
	protected $text=null;
	protected $tarea=null;
	public function getUserName(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_name;
	}
	
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
		$this->tvalidate = 'dijit.form.ValidationTextBox';
		$this->filter = 'dijit.form.FilteringSelect';
		$this->text = 'dijit.form.TextBox';
		$this->tarea = 'dijit.form.SimpleTextarea';
	}
	
	public function FrmPayment($data=null){
		
		$db = new Application_Model_DbTable_DbGlobal();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_title = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_title->setAttribs(array('dojoType'=>$this->tvalidate,
				'onkeyup'=>'this.submit()',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH")
		));
		$_title->setValue($request->getParam("adv_search"));
		
		
		$_status_search=  new Zend_Dojo_Form_Element_FilteringSelect('status_search');
		$_status_search->setAttribs(array('dojoType'=>$this->filter,'class'=>'fullside',));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status_search->setMultiOptions($_status_opt);
		$_status_search->setValue($request->getParam(""));
		
		$_btn_search = new Zend_Dojo_Form_Element_SubmitButton('btn_search');
		$_btn_search->setAttribs(array(
				'dojoType'=>'dijit.form.Button',
				'iconclass'=>'dijitIconSearch',
				'class'=>'fullside',
		));
		$label=$this->tr->translate("SEARCH");
		$_btn_search->setLabel("SEARCh");
		
	
		
		$start_date=new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$start_date->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				));
		$start_date->setValue(date('Y-m-d'));
		
		
		$end_date=new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$end_date->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$end_date->setValue(date('Y-m-d'));
		
		
		
		
		$service_price=new Zend_Dojo_Form_Element_NumberTextBox('service_price');
		$service_price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true
		));
	
	
		$note=new Zend_Dojo_Form_Element_TextBox('note');
		$note->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
	
		
		$status = new Zend_Dojo_Form_Element_FilteringSelect('status');
		$status ->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				));
		$options= array(1=>"ប្រើប្រាស់",0=>"មិនប្រើប្រាស់");
		$status->setMultiOptions($options);
		
		$Dateline=new Zend_Dojo_Form_Element_DateTextBox('deadline');
		$Dateline->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$Dateline->setValue(date('Y-m-d'));
		
		/* $Datesearch_start=new Zend_Dojo_Form_Element_DateTextBox('Datesearch_start');
		$Datesearch_start->setAttribs(array(
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'dojoType'=>'dijit.form.DateTextBox',
				'onchange'=>'CalculateDate();'
		));
		$Datesearch_start->setValue(date('Y-m-d')); */
		$Datesearch_start = new Zend_Dojo_Form_Element_DateTextBox('Datesearch_start');
		$Datesearch_start->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("Datesearch_start");
		
		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$Datesearch_start->setValue($_date);

		$Datesearch_stop = new Zend_Dojo_Form_Element_DateTextBox('Datesearch_stop');
		$Datesearch_stop->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("Datesearch_stop");
		
		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$Datesearch_stop->setValue($_date);
		
		
		
		$customname=new Zend_Dojo_Form_Element_TextBox('customername');
		$customname->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		
		$address=new Zend_Dojo_Form_Element_TextBox('address');
		$address->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		
		$code=new Zend_Dojo_Form_Element_TextBox('code');
		$code->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		$code_search=new Zend_Dojo_Form_Element_TextBox('code_search');
		$code_search->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("បញ្ចូលលេខកូដដើម្បីស្វែងរក")
		));
		
		
		
		$moneyto_pay=new Zend_Dojo_Form_Element_TextBox('moneyto_pay');
		$moneyto_pay->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		
		$input_money=new Zend_Dojo_Form_Element_TextBox('input_money');
		$input_money->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
		));
	
		$money_debts=new Zend_Dojo_Form_Element_TextBox('money_debts');
		$money_debts->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		$service_price=new Zend_Dojo_Form_Element_TextBox('service_price');
		$service_price->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'readonly'=>'true',
		));
	
		$old_conter=new Zend_Dojo_Form_Element_TextBox('old_conter');
		$old_conter->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));
		
		$new_conter=new Zend_Dojo_Form_Element_TextBox('new_conter');
		$new_conter->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				
		));
		$unit_price=new Zend_Dojo_Form_Element_TextBox('unit_price');
		$unit_price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
					'readonly'=>'true',
		
		));
		
		$phone=new Zend_Dojo_Form_Element_TextBox('phone');
		$phone->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
		));

		/*	this place for update*/
		$id = new Zend_Form_Element_Hidden("id");
		if($data!=null){
			$customname->setValue($data['customname']);
			$money_debts->setValue($data['money_debts']);
			$old_conter->setValue($data['old_conter']);
			$new_conter->setValue($data['new_conter']);
			$moneyto_pay->setValue($data['moneyto_pay']);
			$input_money->setValue($data['input_money']);
			$start_date->setValue($data['start_date']);
			$end_date->setValue($data['end_date']);
			$Dateline->setValue($data['deadline']);
			$phone->setValue($data['phone']);
			$address->setValue($data['address']);
			$code->setValue($data['code']);
			$service_price->setValue($data['service_price']); 
			$unit_price->setValue($data['unit_price']);
			$code_search->setValue($data['code_search']);
			
			
		}
		/*	this place for all set value can use in index add or edi so we dont need to write html direc */
		$this->addElements(array($code_search,$unit_price,$service_price,$code,$address,$phone,$Dateline,$customname,$money_debts,$old_conter,$new_conter,$moneyto_pay,$input_money,$start_date,$end_date));
		return $this;
		
	}	
}
/* $price->setValue($data['price']);
 $note->setValue($data['note']);
	
$date_stop->setValue($data['date_stop']);
$Date->setValue($data['date_start']);
$status->setValue($data['status']);
$Dateline->setValue($data['deadline']);
$service_price->setValue($data['service_price']); */
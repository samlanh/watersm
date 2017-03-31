<?php
Class Payment_Form_FrmPayment extends Zend_Dojo_Form {
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
	public function getUserId(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->user_id;

	}
	public function getUserSession(){
		$session_user=new Zend_Session_Namespace('auth');
		return $session_user->first_name;
	}
	public function FrmPayment($data=null){

		$db = new Application_Model_DbTable_DbGlobal();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_title = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_title->setAttribs(array('dojoType'=>$this->tvalidate,
				'onkeyup'=>'this.submit()',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH"),
			'style'=>'padding-left:5px;'
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
			'style'=>'padding-left:5px;'
		));
		$label=$this->tr->translate("SEARCH");
		$_btn_search->setLabel("SEARCh");


		$village = new Zend_Dojo_Form_Element_FilteringSelect('village_1');
		$village->setAttribs(array(
			'dojoType'=>'dijit.form.FilteringSelect',
			'class'=>'fullside',
			'onchange'=>'getvillagecode();',
			'style'=>'padding-left:5px;'
		));
		$rows =  $db->getVillage();
		$options_villag=array($this->tr->translate("")); //array(''=>"------Village------",-1=>"Add New");
		if(!empty($rows))foreach($rows AS $row) $options_villag[$row['id']]=$row['village_name'];
		$village->setMultiOptions($options_villag);

		$search_option_number = new Zend_Dojo_Form_Element_FilteringSelect('search_option_number');
		$search_option_number->setAttribs(array(
			'dojoType'=>'dijit.form.FilteringSelect',
			'class'=>'fullside',
			'onchange'=>'getClientNum();',

		));

		$rows_ed =  $db->getClientNumer();
		$options_number_user=array($this->tr->translate("")); //array(''=>"------Village------",-1=>"Add New");
		if(!empty($rows_ed))foreach($rows_ed AS $row1) $options_number_user[$row1['client_num']]=$row1['client_num'];
		$search_option_number->setMultiOptions($options_number_user);


		$start_date=new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$start_date->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
			'style'=>'padding-left:5px;'
				));
		$start_date->setValue(date('Y-m-d'));

		$end_date=new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$end_date->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
			'style'=>'padding-left:5px;'
		));
		$end_date->setValue(date('Y-m-d'));




		$service_price=new Zend_Dojo_Form_Element_NumberTextBox('service_price');
		$service_price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true,
			'style'=>'padding-left:5px;'
		));


		$note=new Zend_Dojo_Form_Element_TextBox('note');
		$note->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
			'style'=>'padding-left:5px;'
		));


		$status = new Zend_Dojo_Form_Element_FilteringSelect('status');
		$status ->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
			'style'=>'padding-left:5px;'
				));
		$options= array(1=>"ប្រើប្រាស់",0=>"មិនប្រើប្រាស់");
		$status->setMultiOptions($options);

		$Dateline=new Zend_Dojo_Form_Element_DateTextBox('deadline');
		$Dateline->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
			'style'=>'padding-left:5px;'
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
				'onchange'=>'CalculateDate();',
				'style'=>'padding-left:5px;'
		)

		);
		$_date = $request->getParam("Datesearch_start");

		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$Datesearch_start->setValue($_date);

		$Datesearch_stop = new Zend_Dojo_Form_Element_DateTextBox('Datesearch_stop');
		$Datesearch_stop->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'CalculateDate();',
			'style'=>'padding-left:5px;'
			));
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
			'style'=>'padding-left:5px;'
		));

		$address=new Zend_Dojo_Form_Element_TextBox('address');
		$address->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
			'style'=>'padding-left:5px;'
		));

		$code=new Zend_Dojo_Form_Element_TextBox('code');
		$code->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
			'required'=>'true',
			'style'=>'padding-left:5px;'
		));
		$code_search=new Zend_Dojo_Form_Element_TextBox('code_search');
		$code_search->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("បញ្ចូលលេខកូដដើម្បីស្វែងរក"),
			'style'=>'padding-left:5px;'
		));



		$moneyto_pay=new Zend_Dojo_Form_Element_TextBox('moneyto_pay');
		$moneyto_pay->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
			'style'=>'padding-left:5px;color:red'
		));
		$total_full_pay=new Zend_Dojo_Form_Element_TextBox('total_full_pay');
		$total_full_pay->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'required'=>'true',
			'style'=>'padding-left:5px;color:red;'
		));

		$input_money=new Zend_Dojo_Form_Element_TextBox('input_money');
		$input_money->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'required'=>'true',
			'onkeyup'=>'calculate()',
			'style'=>'padding-left:5px;'
		));
		$maintanance_service=new Zend_Dojo_Form_Element_TextBox('maintanance_service');
		$maintanance_service->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'required'=>'true',
			'readonly'=>'true',
			'style'=>'padding-left:5px;'
		));

		$customername_id=new Zend_Form_Element_Hidden('customername_id');
	//	$user_id=new Zend_Form_Element_Hidden('user_id');
		$village_id=new Zend_Form_Element_Hidden('village_id');
		$seting_price_id=new Zend_Form_Element_Hidden('seting_price_id');
		$used_id=new Zend_Form_Element_Hidden('used_id');



		$old_owed=new Zend_Dojo_Form_Element_TextBox('old_owed');
		$old_owed->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'readonly'=>'true',
			'style'=>'color:red',
			'style'=>'padding-left:5px;'
		));
		$old_owed->setValue(5000);

		$new_owed=new Zend_Dojo_Form_Element_TextBox('new_owed');
		$new_owed->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'readonly'=>'true',
			'required'=>'true',
			'style'=>'padding-left:5px;color:red;'

		));


		$date_input=new Zend_Dojo_Form_Element_DateTextBox('date_input');
		$date_input->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
			'style'=>'padding-left:5px;'
		));
		$date_input->setValue(date('Y-m-d'));

		$service_price=new Zend_Dojo_Form_Element_TextBox('service_price');
		$service_price->setAttribs(array(
			'dojoType'=>'dijit.form.NumberTextBox',
			'class'=>'fullside',
			'readonly'=>'true',
			'style'=>'padding-left:5px;'
		));

		$old_conter=new Zend_Dojo_Form_Element_TextBox('old_conter');
		$old_conter->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
			'style'=>'padding-left:5px;'
		));

		$new_conter=new Zend_Dojo_Form_Element_TextBox('new_conter');
		$new_conter->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true',
			'style'=>'padding-left:5px;'

		));
		$unit_price=new Zend_Dojo_Form_Element_TextBox('unit_price');
		$unit_price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
					'readonly'=>'true',
			'style'=>'padding-left:5px;'

		));

		$phone=new Zend_Dojo_Form_Element_TextBox('phone');
		$phone->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'true',
				'style'=>'padding-left:5px;'
		));
		$user_id=new Zend_Form_Element_Hidden('user_id');

		$user_name=new Zend_Dojo_Form_Element_TextBox('user_name');
		$user_name->setAttribs(array(
			'dojoType'=>'dijit.form.TextBox',
			'class'=>'fullside',
			'readonly'=>'true',
			'style'=>'padding-left:5px;'
		));
		$user_name->setValue($this->getUserSession());
		/*	this place for update*/
		$id = new Zend_Form_Element_Hidden("id");
		if($data!=null){
			$customname->setValue($data['client_kh']);
			$date_input->setValue($data['date_input']);
			$old_conter->setValue($data['stat_use']);
			$new_conter->setValue($data['end_use']);
			$moneyto_pay->setValue($data['total_price']);
			$new_owed->setValue($data['owed_next_month']);
			$input_money->setValue($data['input_pay']);
			$start_date->setValue($data['date_start']);
			$end_date->setValue($data['date_stop']);
			$Dateline->setValue($data['deadline']);
			$phone->setValue($data['phone_number']);
			$address->setValue($data['village']);
			$code->setValue($data['client_number']);
			$unit_price->setValue($data['Sett_price']);
			//$customername_id->setValue($data['client_kh']);
			$old_owed->setValue($data['owed_last_month']);
			//$user_id->setValue($data['user_id']);
			//$village_id->setValue($data['village_id']);
			//$seting_price_id->setValue($data['seting_price_id']);
			//$used_id->setValue($data['id']);
			$total_full_pay->setValue($data['total_payment']);
			$maintanance_service->setValue($data['maintanance_service']);
			$user_id->setValue($this->getUserId());
			$user_name->setValue($this->getUserSession());
		}
		/*	this place for all set value can use in index add or edi so we dont need to write html direc */
	
		$this->addElements(array($user_name,$user_id,$maintanance_service,$total_full_pay,$search_option_number,$village,$used_id,$seting_price_id,$village_id,$customername_id,$total_full_pay,$new_owed,$old_owed,$code_search,$unit_price,$service_price,$code,$address,$phone,$Dateline,$customname,$date_input,$old_conter,$new_conter,$moneyto_pay,$input_money,$start_date,$end_date));
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
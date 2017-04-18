<?php 
Class Group_Form_Frmsettingprice extends Zend_Dojo_Form {
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
	
	public function Frmsettingprice($data=null){
		
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
		
		$Date=new Zend_Dojo_Form_Element_DateTextBox('date_start');
		$Date->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
				));
		$Date->setValue(date('Y-m-d'));
		
		$date_stop=new Zend_Dojo_Form_Element_DateTextBox('date_stop');
		$date_stop->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
				'style'=>'color:red'
		));
		$date_stop->setValue(date('Y-m-d'));

		$earning_stop=new Zend_Dojo_Form_Element_DateTextBox('earning_stop');
		$earning_stop->setAttribs(array(
			'dojoType'=>'dijit.form.DateTextBox',
			'class'=>'fullside',
			'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
			'style'=>'color:red'
		));
		$earning_stop->setValue(date('Y-m-d'));

		$earning_start=new Zend_Dojo_Form_Element_DateTextBox('earning_start');
		$earning_start->setAttribs(array(
			'dojoType'=>'dijit.form.DateTextBox',
			'class'=>'fullside',
			'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
		));
		$earning_start->setValue(date('Y-m-d'));

		
		$price=new Zend_Dojo_Form_Element_NumberTextBox('price');
		$price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true
		));
		$service_price=new Zend_Dojo_Form_Element_NumberTextBox('service_price');
		$service_price->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true
		));
		$maintanance_service=new Zend_Dojo_Form_Element_NumberTextBox('maintanance_service');
		$maintanance_service->setAttribs(array(
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
				'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
			'style'=>'color:blue;font-weight:bold'
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
				'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("Datesearch_start");
		
		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$Datesearch_start->setValue($_date);
		


	/* 	$Datesearch_stop=new Zend_Dojo_Form_Element_DateTextBox('Datesearch_stop');
		$Datesearch_stop->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'required' =>'true',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'calCulateEndDate();'
		));
		$Datesearch_stop->setValue(date('Y-m-d')); */
		
		
		
		
		$Datesearch_stop = new Zend_Dojo_Form_Element_DateTextBox('Datesearch_stop');
		$Datesearch_stop->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MMM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("Datesearch_stop");
		
		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$Datesearch_stop->setValue($_date);
		
		
		
		
		
		
		
		
		/*	this place for update*/
		$id = new Zend_Form_Element_Hidden("id");
		if($data!=null){
			
			$price->setValue($data['price']);
			$note->setValue($data['note']);
			
			$date_stop->setValue($data['date_stop']);
			$Date->setValue($data['date_start']);
			$status->setValue($data['status']);
			$Dateline->setValue($data['deadline']);
			$service_price->setValue($data['service_price']);
			$earning_start->setValue($data['earning_start']);
			$earning_stop->setValue($data['earning_stop']);
			$maintanance_service->setValue($data['maintanance_service']);
		}
		
		
		/*	this place for all set value can use in index add or edi so we dont need to write html direc */
		$this->addElements(array($maintanance_service,$earning_start,$earning_stop,$_title,$price,$service_price,$note,$Date,$status,$_btn_search,$_status_search,$Dateline,$date_stop,$Datesearch_start,$Datesearch_stop));
		return $this;
		
	}	
}
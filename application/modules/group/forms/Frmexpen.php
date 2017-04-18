<?php 
Class Group_Form_Frmexpen extends Zend_Dojo_Form {
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
	
	public function frmexpend($data=null){
		
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
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				));
		$Date->setValue(date('Y-m-d'));
		
		$paid_day=new Zend_Dojo_Form_Element_DateTextBox('paid_day');
		$paid_day->setAttribs(array(
				'dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$paid_day->setValue(date('Y-m-d'));
		
		$unit=new Zend_Dojo_Form_Element_NumberTextBox('unit');
		$unit->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>true
		));

		$total_Money=new Zend_Dojo_Form_Element_NumberTextBox('total_Money');
		$total_Money->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'readonly'=>'true'
		));

		$priceperunit=new Zend_Dojo_Form_Element_NumberTextBox('priceperunit');
		$priceperunit->setAttribs(array(
				'dojoType'=>'dijit.form.NumberTextBox',
				'class'=>'fullside',
				'required'=>'true',
				'onkeyup'=>'calExpense()'
		));
	
	
		$note=new Zend_Dojo_Form_Element_TextBox('note');
		$note->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));

		$payfor=new Zend_Dojo_Form_Element_TextBox('payfor');
		$payfor->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'required'=>true
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
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$Dateline->setValue(date('Y-m-d'));
		
		
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
		
		$village_name = new Zend_Dojo_Form_Element_FilteringSelect("village_name");
		$opt_village = array(''=>'ជ្រើសរើស ឈ្មោះភូមិ',);
		$rows = $db->getAllvillage();
		
		if(!empty($rows))foreach($rows AS $row){
			$opt_village[$row['vill_id']]=$row['village_namekh'];

		}
		$village_name->setMultiOptions($opt_village);
		$village_name->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect',
										'class'=>'fullside',
										'onchange'=>'getvillagecode();'));
		$village_name->setValue($request->getParam("village_namekh"));
		
		/*	this place for update*/
		$id = new Zend_Form_Element_Hidden("id");
		if($data!=null){

			$village_name->setValue($data['village_id']);
			$payfor->setValue($data['expen_title']);
			$unit->setValue($data['unit']);
			$priceperunit->setValue($data['price']);
			$total_Money->setValue($data['total_price']);
			$paid_day->setValue($data['date_expen']);
			$note->setValue($data['description']);
			$status->setValue($data['status']);
		}
		/*	this place for all set value can use in index add or edi so we dont need to write html direc */
		$this->addElements(array($village_name,$total_Money,$_title,$payfor,$unit,$priceperunit,$note,$Date,$status,$_btn_search,$_status_search,$Dateline,$paid_day,$Datesearch_start,$Datesearch_stop));
		return $this;
		
	}	
}
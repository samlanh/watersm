<?php 
Class Loan_Form_FrmSearchLoan extends Zend_Dojo_Form {
	protected $tr;
	public function init()
	{
		//$baseurl =  Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function AdvanceSearch($data=null){
		
		$db = new Application_Model_DbTable_DbGlobal();
		
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside'));
		$_status_opt = array(
				-1=>$this->tr->translate("ALL"),
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);
		$_status->setValue($request->getParam("status"));
		
		$_title = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_title->setAttribs(array('dojoType'=>'dijit.form.TextBox',
				'onkeyup'=>'this.submit()',
				'class'=>'fullside',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH")
		));
		$_title->setValue($request->getParam("adv_search"));
		
		$_btn_search = new Zend_Dojo_Form_Element_SubmitButton('btn_search');
		$_btn_search->setAttribs(array(
				'dojoType'=>'dijit.form.Button',
				'iconclass'=>'dijitIconSearch',
				'class'=>'fullside',
		
		));
		
		$land_id = new Zend_Dojo_Form_Element_FilteringSelect('land_id');
		$land_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				));
		$options = $db ->getAllLandInfo(null,null,1);//show name,show group,show option
		$land_id->setMultiOptions($options);
		$land_id->setValue($request->getParam("land_id"));

		$schedule_opt = new Zend_Dojo_Form_Element_FilteringSelect('schedule_opt');
		$schedule_opt->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'checkScheduleOption();'
		));
		$rows = $db->getVewOptoinTypeByType(25,null,null,1);
		$options = array(-1=>"ជ្រើសរើសប្រភេទបង់ប្រាក់");
	    if(!empty($rows))foreach($rows AS $row){
  			$options[$row['key_code']]=$row['name_en'];//($row['displayby']==1)?$row['name_kh']:$row['name_en'];
  		}
// 		print_r($opt);exit();
// 		៊៊$opt[-1]="ជ្រើសរើសប្រភេទបង់ប្រាក់";
// 		array_unshift($opt, array(1=>'ជ្រើសរើសប្រភេទបង់ប្រាក់') );
// 		print_r($opt);exit();
		$schedule_opt->setMultiOptions($options);
		$schedule_opt->setValue($request->getParam("schedule_opt"));
		
		
		$_coid = new Zend_Dojo_Form_Element_FilteringSelect('co_id');
		$_coid->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'popupCheckCO();'
		));
		$options = $db ->getAllCOName(1);
		$_coid->setMultiOptions($options);
		$_coid->setValue($request->getParam("co_id"));
		
		$_releasedate = new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$_releasedate->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("start_date");
		
		if(empty($_date)){
			//$_date = date('Y-m-d');
		}
		$_releasedate->setValue($_date);
		
		
		$_dateline = new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$_dateline->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','required'=>'true',
				'class'=>'fullside',
				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$_date = $request->getParam("end_date");
		
		if(empty($_date)){
			$_date = date("Y-m-d");
		}
		$_dateline->setValue($_date);
		
		$client_name = new Zend_Dojo_Form_Element_FilteringSelect("client_name");
		$opt_client = array(''=>'ជ្រើសរើស ឈ្មោះអតិថិជន',);
		$rows = $db->getAllClient();
		if(!empty($rows))foreach($rows AS $row){
			$opt_client[$row['id']]=$row['name'];
		}
		$client_name->setMultiOptions($opt_client);
		$client_name->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		$client_name->setValue($request->getParam("client_name"));
		
		
		
		
		$_branch_id = new Zend_Dojo_Form_Element_FilteringSelect('branch_id');
		$_branch_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		
		$options = $db->getAllBranchName(null,1);
		$_branch_id->setMultiOptions($options);
		$_branch_id->setValue($request->getParam("branch_id"));
		
		
		
		
		
		
		
		
		$propertiestype = new Zend_Dojo_Form_Element_FilteringSelect('property_type');
		$propertiestype->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside','onChange'=>'showPopupForm();'));
		$propertiestype_opt = $db->getPropertyTypeForsearch();
		$propertiestype->setMultiOptions($propertiestype_opt);
		$propertiestype->setValue($request->getParam("property_type"));
		
		$_category = new Zend_Dojo_Form_Element_FilteringSelect('category_id');
		$_category->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt= $db->getVewOptoinTypeByType(12,1,null,null);
		if($request->getActionName()!='add' OR $request->getActionName()!='edit'){
			unset($opt[-1]);
		}
		$_category->setMultiOptions($opt);
		$_category->setValue($request->getParam("category_id"));
		
		$category_id_expense = new Zend_Dojo_Form_Element_FilteringSelect('category_id_expense');
		$category_id_expense->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt1= $db->getVewOptoinTypeByType(13,1,null,null);
		if($request->getActionName()!='add' OR $request->getActionName()!='edit'){
			unset($opt1[-1]);
		}
		$category_id_expense->setMultiOptions($opt1);
		$category_id_expense->setValue($request->getParam("category_id_expense"));
		
		$payment_type = new Zend_Dojo_Form_Element_FilteringSelect('payment_type');
		$payment_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside'
		));
		$options= array(-1=>"ជ្រើសរើសចំណាយជា",1=>"សាច់ប្រាក់",2=>"សែក");
		$payment_type->setMultiOptions($options);
		$payment_type->setValue($request->getParam("payment_type"));
		
		$buy_type = new Zend_Dojo_Form_Element_FilteringSelect('buy_type');
		$buy_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside'
		));
		$options= array(-1=>"ជ្រើសរើសការលក់",1=>"ធ្វើកិច្ចសន្យា",2=>"ប្រាក់កក់");
		$buy_type->setMultiOptions($options);
		$buy_type->setValue($request->getParam("buy_type"));
		
		
		
		
		
		$village_name = new Zend_Dojo_Form_Element_FilteringSelect("village_name");
		$opt_village = array(''=>'ជ្រើសរើស ឈ្មោះភូមិ',
							
				);
		$rows = $db->getAllvillage();
		
		if(!empty($rows))foreach($rows AS $row){
			$opt_village[$row['vill_id']]=$row['village_namekh'];

		}
		
		
		
		$village_name->setMultiOptions($opt_village);
		$village_name->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect',
										'class'=>'fullside',
										'onchange'=>'getvillagecode();'));
		$village_name->setValue($request->getParam("village_namekh"));
		
		/*This is my code*/
		
		
		if($data!=null){
// 			$_member->setValue($data['client_id']);
			$_coid->setValue($data['co_id']);
			$_releasedate->setValue($data['date_release']);
			$client_name->setValue($data['client_name']);
			$village_name->setValue($data['village_name']);
		}
		$this->addElements(array($buy_type,$payment_type,$land_id,$propertiestype,$schedule_opt,$_branch_id,$client_name,$_title,$_coid,$_releasedate,
				$_category,$category_id_expense,$village_name,
		// 				$_groupid,$_member,$_group_code,$_customer_code,
				$_dateline,$_status,$_btn_search));
		return $this;
		
	}	
	function JurnalSearch($data=null){
		
		
		$db = new Application_Model_DbTable_DbGlobal();
		$request=Zend_Controller_Front::getInstance()->getRequest();
		
		$_currency_type = new Zend_Dojo_Form_Element_FilteringSelect('currency_type');
		$_currency_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt = $db->getVewOptoinTypeByType(15,1,3,1);
		$opt['-1']=("--Select Currency Type--");
		$_currency_type->setMultiOptions($opt);
		
		$_valuecurr=$request->getParam("currency_type");
		if(empty($_valuecurr) AND $_valuecurr!=-1){
			$_currency_type->setValue(-1);
		}else{
			$_currency_type->setValue($_valuecurr);
		}
		
		
		$_title = new Zend_Dojo_Form_Element_TextBox('adv_search');
		$_title->setAttribs(array('dojoType'=>'dijit.form.TextBox',
				'onkeyup'=>'this.submit()',
				'placeholder'=>$this->tr->translate("ADVANCE_SEARCH")
		));
		$_title->setValue($request->getParam("adv_search"));
		
		
		$_releasedate = new Zend_Dojo_Form_Element_DateTextBox('start_date');
		$_releasedate->setAttribs(array('dojoType'=>'dijit.form.DateTextBox',
		// 				'class'=>'fullside',
				'onchange'=>'CalculateDate();'));
		$_date = $request->getParam("start_date");
		
		if(empty($_date)){
			$_date = date('Y-m-d');
		}
		$_releasedate->setValue($_date);
		
		
		$_dateline = new Zend_Dojo_Form_Element_DateTextBox('end_date');
		$_dateline->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','required'=>'true',
		// 				'class'=>'fullside',
		));
		$_date = $request->getParam("end_date");
		
		if(empty($_date)){
			$_date = date("Y-m-d");
		}
		$_dateline->setValue($_date);
		
		
		$_branch_id = new Zend_Dojo_Form_Element_FilteringSelect('branch_id');
		$_branch_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
		));
		
		$rows = $db->getAllBranchName();
		$options=array(-1=>'---Select Branch---');
		if(!empty($rows))foreach($rows AS $row){
			$options[$row['br_id']]=$row['branch_namekh'];
		}
		$_branch_id->setMultiOptions($options);
		$_branch_id->setValue($request->getParam("branch_id"));
		
// 		$date=new Zend_Dojo_Form_Element_DateTextBox('date');
// 		$date->setAttribs(array(
// 				'dojoType'=>'dijit.form.DateTextBox',
// 				'class'=>'fullside',
// 				'constraints'=>"{datePattern:'dd/MM/yyyy'}",
// 		));
// 		$date->setValue(date('Y-m-d'));
		
		
		if($data!=null){
			//print_r($data);
			$_branch_id->setValue($data['member_id']);
			$_releasedate->setValue($data['date_release']);
			$_currency_type->setValue($data['payment_method']);
		}
		$this->addElements(array($_title,$_branch_id,$_currency_type,$_releasedate,$_dateline));
		return $this;
		
	}
}
?>

	
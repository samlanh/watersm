<?php
Class Group_Form_FrmClient extends Zend_Dojo_Form {
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmAddClient($data=null){
		$clienttype_nameen= new Zend_Dojo_Form_Element_DateTextBox('clienttype_nameen');
		$clienttype_nameen->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'
		));
		$clienttype_namekh= new Zend_Dojo_Form_Element_DateTextBox('clienttype_namekh');
		$clienttype_namekh->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'
		));
		$_dob= new Zend_Dojo_Form_Element_DateTextBox('dob_client');
		$_dob->setAttribs(array('dojoType'=>'dijit.form.DateTextBox','class'=>'fullside','constraints'=>"{datePattern:'dd/MM/yyyy'}",
		));
		$request=Zend_Controller_Front::getInstance()->getRequest();
		$db = new Application_Model_DbTable_DbGlobal();
		$db1=new Group_Model_DbTable_DbClient();

		$branch_id = new Zend_Dojo_Form_Element_FilteringSelect('branch_id');
		$branch_id->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'required' =>'true',
				'onchange'=>'getClientNo();'
		));

		$rows_branch = $db->getAllBranchName();
		//=array(''=>"---Select Branch---");
		if(!empty($rows_branch))foreach($rows_branch AS $row){
			$options_branch[$row['br_id']]=$row['project_name'];
		}
		$branch_id->setMultiOptions($options_branch);
		$branch_id->setValue($request->getParam("branch_id"));


		$_cust_select = new Zend_Dojo_Form_Element_FilteringSelect('cust_select');
		$_cust_select->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'required' =>'true',
				//'onchange'=>'getClientNo();'
		));

		$_member = new Zend_Dojo_Form_Element_FilteringSelect('customer_id');
		$_member->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'getGroupCode();'
		));
		$db = new Application_Model_DbTable_DbGlobal();
// 		$rows = $db->getClientByType();
		$opt_client=array(-1=>"áž‡áŸ’ážšáž¾ážŸážšáž¾ážŸáž¢áž�áž·áž�áž·áž‡áž“");
// 		if(!empty($rows))foreach($rows AS $row) $options[$row['client_id']]=$row['name_en'];
// 		$_member->setMultiOptions($options);

		$rows = $db->getAllClient();
		if(!empty($rows))foreach($rows AS $row){
			$opt_client[$row['id']]=$row['name'];
		}
		$_member->setMultiOptions($opt_client);
		$_member->setValue($request->getParam("customer_id"));

		$_namekh = new Zend_Dojo_Form_Element_TextBox('name_kh');
		$_namekh->setAttribs(array(
						'dojoType'=>'dijit.form.ValidationTextBox',
						'class'=>'fullside',
						'required' =>'true'
		));

 		$id_client = $db->getNewClientId();

		$_clientno = new Zend_Dojo_Form_Element_TextBox('client_no');
		$_clientno->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				'readonly'=>'readonly',
				'style'=>'color:red;'
		));
		$_clientno->setValue("C".$id_client);

		$id_client = $db1->get_base_service();
		$_unit_price = new Zend_Dojo_Form_Element_NumberTextBox('unit_price');
		$_unit_price->setAttribs(array(
			'dojoType'=>'dijit.form.TextBox',
			'class'=>'fullside',
			'readonly'=>'readonly',
			'style'=>'color:red;'
		));
		$_unit_price->setValue($id_client);

		$_date_cus_start_month=$db1->get_cus_start();
		$_date_cus_start= new Zend_Dojo_Form_Element_DateTextBox('date_cus_start');
		$_date_cus_start->setAttribs(array(
			'dojoType'=>'dijit.form.DateTextBox',
			'class'=>'fullside',
			'constraints'=>"{datePattern:'dd/MM/yyyy'}",
			'readonly'=>'true',
		));
		
		$_date_cus_start->setValue($_date_cus_start_month);
		$id_setting_price = $db1->get_setting_id();
		$_price_per_use_id = new Zend_Dojo_Form_Element_NumberTextBox('price_per_use_id');
		$_price_per_use_id->setAttribs(array(
			'dojoType'=>'dijit.form.TextBox',
			'class'=>'fullside',
			'readonly'=>'readonly',
			'style'=>'color:red;'
		));
		$_price_per_use_id->setValue($id_setting_price);

		$setting_price = $db1->get_setting_price();
		$_price_per_use = new Zend_Form_Element_Hidden('price_per_use');
		$_price_per_use->setAttribs(array(
			'dojoType'=>'dijit.form.TextBox',
			'class'=>'fullside',
			'readonly'=>'readonly',
			'style'=>'color:red;'
		));
		$_price_per_use->setValue($setting_price);


		$_village_code = new Zend_Dojo_Form_Element_TextBox('village_code');
		$_village_code->setAttribs(array(
			'dojoType'=>'dijit.form.TextBox',
			'class'=>'fullside',
			'readonly'=>'readonly',
			'required' =>'true',
			'style'=>'color:red;'
		));

		$_nameen = new Zend_Dojo_Form_Element_TextBox('name_en');
		$_nameen->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				));

		$_join_with = new Zend_Dojo_Form_Element_TextBox('join_with');
		$_join_with->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));

		$_join_nation_id = new Zend_Dojo_Form_Element_TextBox('join_nation_id');
		$_join_nation_id->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));

		$_nationality = new Zend_Dojo_Form_Element_TextBox('nationality');
		$_nationality->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$_nationality->setValue("áž�áŸ’áž˜áŸ‚ážš");

		$p_nationality = new Zend_Dojo_Form_Element_TextBox('p_nationality');
		$p_nationality->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$p_nationality->setValue("áž�áŸ’áž˜áŸ‚ážš");

		$_sex = new Zend_Dojo_Form_Element_FilteringSelect('sex');
		$_sex->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt_status = $db->getVewOptoinTypeByType(11,1);
		unset($opt_status[-1]);
		unset($opt_status['']);
		$_sex->setMultiOptions($opt_status);

		$client_d_type = new Zend_Dojo_Form_Element_FilteringSelect('client_d_type');
		$client_d_type->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));

		$_province = new Zend_Dojo_Form_Element_FilteringSelect('province');
		$_province->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'filterDistrict();',
		));

		$rows =  $db->getAllProvince();

		$_id_no = new Zend_Dojo_Form_Element_TextBox('id_no');
		$_id_no->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				'required' =>'true'
		));

		$_phone = new Zend_Dojo_Form_Element_TextBox('phone');
		$_phone->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$photo=new Zend_Form_Element_File('photo');
		$photo->setAttribs(array(
		));
		$job = new Zend_Dojo_Form_Element_TextBox('job');
		$job->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$national_id=new Zend_Dojo_Form_Element_TextBox('national_id');
		$national_id->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				));

		$referecce_national_id=new Zend_Dojo_Form_Element_TextBox('reference_national_id');
		$referecce_national_id->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));

		$_id = new Zend_Form_Element_Hidden("id");
		$_desc = new Zend_Dojo_Form_Element_TextBox('desc');
		$_desc->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside',
				'style'=>'width:96%;min-height:50px;'));

		$_status=  new Zend_Dojo_Form_Element_FilteringSelect('status');
		$_status->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		$_status_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_status->setMultiOptions($_status_opt);

		$_join_type =  new Zend_Dojo_Form_Element_TextBox('join_type');
		$_join_type->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside',));

		$_hnamekh = new Zend_Dojo_Form_Element_TextBox('hname_kh');
		$_hnamekh->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				//'required' =>'true'
		));
		$_price_service = new Zend_Dojo_Form_Element_TextBox('price_service');
		$_price_service->setAttribs(array(
			'dojoType'=>'dijit.form.ValidationTextBox',
			'class'=>'fullside',
			'onkeyup'=>'calculate()',

			//'required' =>'true'
		));
		$_total_service = new Zend_Dojo_Form_Element_TextBox('total_service');
		$_total_service->setAttribs(array(
			'dojoType'=>'dijit.form.ValidationTextBox',
			'class'=>'fullside',
		));



		$_bnamekh = new Zend_Dojo_Form_Element_TextBox('bname_kh');
		$_bnamekh->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				//'required' =>'true'
		));

		$_ksex = new Zend_Dojo_Form_Element_FilteringSelect('ksex');
		$_ksex->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
		));
		$opt_kstatus= $db->getVewOptoinTypeByType(11,1);
		unset($opt_kstatus['-1']);
		unset($opt_kstatus['']);
		$_ksex->setMultiOptions($opt_kstatus);

		$_pnameen = new Zend_Dojo_Form_Element_ValidationTextBox('pname_en');
		$_pnameen->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				'required' =>'true'
		));
		$_cprovince = new Zend_Dojo_Form_Element_FilteringSelect('cprovince');
		$_cprovince->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'dfilterDistrict();',
		));

		$rows =  $db->getAllProvince();
		$options=array($this->tr->translate("SELECT_PROVINCE")); //array(''=>"------Select Province------",-1=>"Add New");
		if(!empty($rows))foreach($rows AS $row) $options[$row['province_id']]=$row['province_en_name'];
		$_cprovince->setMultiOptions($options);




		$_dstreet = new Zend_Dojo_Form_Element_TextBox('dstreet');
		$_dstreet->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$_ghouse = new Zend_Dojo_Form_Element_TextBox('ghouse');
		$_ghouse->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
		));
		$_lphone = new Zend_Dojo_Form_Element_TextBox('lphone');
		$_lphone->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$_istatus=  new Zend_Dojo_Form_Element_FilteringSelect('istatus');
		$_istatus->setAttribs(array('dojoType'=>'dijit.form.FilteringSelect','class'=>'fullside',));
		$_istatus_opt = array(
				1=>$this->tr->translate("ACTIVE"),
				0=>$this->tr->translate("DACTIVE"));
		$_istatus->setMultiOptions($_status_opt);

		$_edesc = new Zend_Dojo_Form_Element_TextBox('edesc');
		$_edesc->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside',
				'style'=>'width:96%;min-height:50px;'));
		$_vid_no = new Zend_Dojo_Form_Element_TextBox('vid_no');
		$_vid_no->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				'required' =>'true'
		));
		$end_used = new Zend_Dojo_Form_Element_TextBox('end_used');
		$end_used->setAttribs(array(
			'dojoType'=>'dijit.form.ValidationTextBox',
			'class'=>'fullside',
			'required' =>'true'
		));
		$_bmember = new Zend_Dojo_Form_Element_FilteringSelect('bgroup_id');
		$_bmember->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'getGroupCode();'
		));
		$_rid_no = new Zend_Dojo_Form_Element_TextBox('rid_no');
		$_rid_no->setAttribs(array(
				'dojoType'=>'dijit.form.ValidationTextBox',
				'class'=>'fullside',
				//'required' =>'true'
		));
		$vl = new Zend_Dojo_Form_Element_TextBox('vl');
		$vl->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				//'required' =>'true'
		));
		$village_ed = new Zend_Dojo_Form_Element_FilteringSelect('village_1_ed');
		$village_ed->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'getvillagecode();',
		));

		$rows_ed =  $db->getVillage();
		$options_villag_ed=array($this->tr->translate("SELECT_VILLAGE")); //array(''=>"------Village------",-1=>"Add New");
		if(!empty($rows_ed))foreach($rows_ed AS $row1) $options_villag_ed[$row1['id']]=$row1['village_name'];
		$village_ed->setMultiOptions($options_villag_ed);



		$village = new Zend_Dojo_Form_Element_FilteringSelect('village_1');
		$village->setAttribs(array(
				'dojoType'=>'dijit.form.FilteringSelect',
				'class'=>'fullside',
				'onchange'=>'getvillagecode();',
		));

		$rows =  $db->getVillage();
		$options_villag=array($this->tr->translate("ជ្រើសរើសភូមិ")); //array(''=>"------Village------",-1=>"Add New");
		if(!empty($rows))foreach($rows AS $row) $options_villag[$row['id']]=$row['village_name'];
		$village->setMultiOptions($options_villag);

		if($data!=null){
			//print_r($data);exit();
			$_namekh->setValue($data['name_kh']);
			$_clientno->setValue($data['client_number']);
			$_sex->setValue($data['sex']);
			$village->setValue($data['village_id']);
			$_village_code->setValue($data['vill_code']);
			$_phone->setValue($data['phone']);
			$_price_service->setValue($data['price_service']);
			$_total_service->setValue($data['total_service']);
			$end_used->setValue($data['total_service']);
			$_desc->setValue($data['remark']);
			$_status->setValue($data['status']);

			}


		$this->addElements(array($end_used,$_total_service,$_price_per_use_id,$_price_per_use,$_unit_price,$_price_service,$village_ed,$_cust_select, $_join_type,$referecce_national_id,$p_nationality,$_nationality,$_rid_no,$_bmember,$_vid_no,$_edesc,$_istatus,$_lphone,$_ghouse,$_dstreet,$_cprovince,$_pnameen,$_bnamekh,$_ksex,$_hnamekh,$client_d_type,$_join_nation_id,
				$_join_with,$_id,$photo,$job,$national_id,$_member,$_village_code,$_namekh,$_nameen,$_sex,
				$_province,$_id_no,$branch_id,
				$_phone,$_desc,$_status,$_clientno,$_date_cus_start,$_dob,$clienttype_namekh,$clienttype_nameen,$village));
		return $this;

	}

}
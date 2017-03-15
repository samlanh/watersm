<?php 
Class Mygroup_Form_FrmIndex extends Zend_Dojo_Form {
	protected $tr;
	public function init()
	{
		$this->tr = Application_Form_FrmLanguages::getCurrentlanguage();
	}
	public function FrmIndex($data=null){
		
		$catagory=new Zend_Dojo_Form_Element_TextBox('catagory');
		$catagory->setAllowEmpty(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
				));
		
		$decription=new Zend_Dojo_Form_Element_TextBox('decription');
		$decription->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		$adv_searchgroup=new Zend_Dojo_Form_Element_TextBox('adv_searchgroup');
		$adv_searchgroup->setAttribs(array(
				'dojoType'=>'dijit.form.TextBox',
				'class'=>'fullside',
		));
		
		$id = new Zend_Form_Element_Hidden("id");
		if($data!=null){
			$catagory->setValue($data['catagory']);
			$decription->setValue($data['decription']);
			
		}
		

		$this->addElements(array($catagory,$decription,$adv_searchgroup));
		return $this;
		
	}
	
}
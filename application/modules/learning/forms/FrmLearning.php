<?php

/**
 * Created by PhpStorm.
 * User: BUNCHHEANG
 * Date: 14-Mar-17
 * Time: 11:35 AM
 */
class Learning_Form_FrmLearning extends Zend_Dojo_Form
{
            public function FrmLearning($data=null){
                $_category=new Zend_Dojo_Form_Element_TextBox('txt_category');
                $_category->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'));

                $_txtDesc=new Zend_Dojo_Form_Element_TextBox('txtDesc');
                $_txtDesc->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'));
                
                $_search=new Zend_Dojo_Form_Element_TextBox('search');
                $_search->setAttribs(array('dojoType'=>'dijit.form.TextBox','class'=>'fullside'));
                

               $this->addElements(array($_category,$_txtDesc,$_search));
                return $this;
            }
}
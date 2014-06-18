<?php

class page_master_fees_head extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$fees=$this->add('Model_FeesHead');
		$crud->setModel($fees);		

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$fees_model = $crud->add('Model_Fees');
			// CreatNew Function call
			$fees_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});	
		
	}
}
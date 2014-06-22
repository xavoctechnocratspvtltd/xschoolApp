<?php

class page_test extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$fees=$this->add('Model_FeesAmount');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$fees_model = $crud->add('Model_FeesAmount');
			// CreatNew Function call
			$fees_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($fees);		
	}
}
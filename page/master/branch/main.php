<?php

class page_master_branch_main extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$branch=$this->add('Model_Branch');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$branch_model = $crud->add('Model_Branch');
			// CreatNew Function call
			$branch_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($branch);		
	}
}
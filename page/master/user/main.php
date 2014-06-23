<?php

class page_master_user_main extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$staff_model_new=$this->add('Model_Staff');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$staff_model = $crud->add('Model_Staff');
			// CreatNew Function call
			$staff_model->createNew($form['name'],$form['username'],$form['password'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($staff_model_new);		

		if($g=$crud->grid){
			$g->addQuickSearch(array('name','branch'));
			$g->addPaginator(10);
		}
	}
}
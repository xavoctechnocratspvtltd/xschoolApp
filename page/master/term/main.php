<?php

class page_master_term_main extends Page {
	function init(){
		parent::init();

		$crud=$this->add('xCRUD');

		$term=$this->add('Model_Term');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$term_model = $crud->add('Model_Term');
			// CreatNew Function call
			$term_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($term);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}


	}
}
<?php

class page_library_subjects extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		// $category=$this->add('Model_Library_Category');	
		$category=$this->api->currentBranch->library_subjects();

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$category_model = $crud->add('Model_Library_Subjects');
			// CreatNew Function call
			$category_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});	
		
		$crud->setModel($category);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));
   
		}

	}
}
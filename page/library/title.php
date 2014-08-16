<?php

class page_library_title extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$title=$this->add('Model_Library_Title');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$title_model = $crud->add('Model_Library_Title');
			// CreatNew Function call
			try{

				$crud->api->db->beginTransaction();
				$title_model->createNew($form['name'],$form->getAllFields(),$form);
				
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			return true; // Always required
		});

		$crud->setModel($title);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));
		}

	}
}
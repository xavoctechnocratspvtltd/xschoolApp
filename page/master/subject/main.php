<?php

class page_master_subject_main extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$subject=$this->add('Model_Subject');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$subject_model = $crud->add('Model_Subject');
			// CreatNew Function call
			try{
				$crud->api->db->beginTransaction();
				$subject_model->createNew($form['name'],$form->getAllFields(),$form);
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			return true; // Always required
		});
		$crud->setModel($subject);	



		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}	
	}
}
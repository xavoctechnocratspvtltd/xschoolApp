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
			try{
				$crud->api->db->beginTransaction();
				$branch_model->createNew($form['name'],$form->getAllFields(),$form);
				$crud->api->db->commit();
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			
			return true; // Always required
		});
		
		$crud->setModel($branch);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name','address','phone_no','principle_name','principle_contact_no'));

		}

	}
}
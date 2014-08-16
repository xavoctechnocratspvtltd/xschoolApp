<?php

class page_library_supplier extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$supplier=$this->add('Model_Supplier');	

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$supplier_model = $crud->add('Model_Supplier');
			// CreatNew Function call
			try{

				$this->api->db->beginTransaction();
				$supplier_model->createNew($form['name'],$form->getAllFields(),$form);
				
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
			}
				
			return true; // Always required
		});

		$crud->setModel($supplier);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}

	}
}
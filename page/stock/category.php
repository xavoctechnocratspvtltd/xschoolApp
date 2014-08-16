<?php

class page_stock_category extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$category=$this->add('Model_Stock_Category');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$category_model = $crud->add('Model_Stock_Category');
			// CreatNew Function call
			try{

				$crud->api->db->beginTransaction();
				$category_model->createNew($form['name'],$form->getAllFields(),$form);
				
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
			}
				

			return true; // Always required
		});
		
		$crud->setModel($category);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);

		}

	}
}
<?php

class page_stock_supplier extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$supplier=$this->add('Model_Stock_Supplier');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$supplier_model = $crud->add('Model_Stock_Supplier');
			// CreatNew Function call
			$supplier_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($supplier);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);

		}

	}
}
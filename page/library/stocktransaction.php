<?php

class page_library_stocktransaction extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$stock_transaction=$this->add('Model_Library_StockTransaction');
		
		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$st_model = $crud->add('Model_Library_StockTransaction');
			// CreatNew Function call
			$st_model->createNew(null,$form->getAllFields(),$form);
			return true; // Always required
			
		});	

		$crud->setModel($stock_transaction);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));
		}
 
	}
}
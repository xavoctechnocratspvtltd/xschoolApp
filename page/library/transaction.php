<?php

class page_library_transaction extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$transaction=$this->add('Model_Library_Transaction');		
		$crud->setModel($transaction);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));
		}

	}
}
<?php

class page_transport_type extends Page{
	function init(){
		parent::init();

		$crud=$this->add('xCRUD');
		$type=$this->add('Model_Vehicle_Type');//$this->api->currentBranch->classes();


		$crud->addHook('myupdate',function($crud,$form){
			
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			
			// Do your stuff by getting $form data
			$vehicle_type = $crud->add('Model_Vehicle_Type');
			// CreatNew Function call
			$vehicle_type->createNew($form['name']);
			return true; // Always required
		});
		
		$crud->setModel($type);		

		if($g=$crud->grid){

			// $g->addPaginator(10);
			$g->addQuickSearch(array('branch','full_name'));
			$g->addTotals(array('no_of_students'));

		}		

		
	}
}
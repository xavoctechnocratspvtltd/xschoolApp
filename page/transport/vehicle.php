<?php

class page_transport_vehicle extends Page{
	function init(){
		parent::init();

		$crud=$this->add('xCRUD');
		$vehicle=$this->api->currentBranch->vehicle();


		$crud->addHook('myupdate',function($crud,$form){
			
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			
			// Do your stuff by getting $form data
			$vehicle_model = $crud->add('Model_Vehicle');
			// CreatNew Function call
			$vehicle_model->createNew($form['name'],$form['capcity'],$form['driver_name'],$form['driver_number']);
			return true; // Always required
		});
		
		$crud->setModel($vehicle);		

		if($g=$crud->grid){

			$g->addPaginator(10);
			$g->addQuickSearch(array('name','driver_number','driver_name'));

		}
	}
}
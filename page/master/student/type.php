<?php

class page_master_student_type extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$fees_type=$this->add('Model_StudentType');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$student_type_model = $crud->add('Model_StudentType');
			// CreatNew Function call
			$student_type_model->createNew($form['name'],$form['previouse_studenttype_id'],$form->getAllFields(),$form);
			return true; // Always required
		});

		if($crud->isEditing()){
		    $o=$crud->form->add('Order');
		}

		if($crud->isEditing('add')){
			
		}	

		$crud->setModel($fees_type);		

		if($crud->isEditing()){
			$o->now();
		}


		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}
	}
}
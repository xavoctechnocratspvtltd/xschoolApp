<?php

class page_master_class_main extends Page {
	function page_index(){
		// parent::init();
	
		$crud=$this->add('xCRUD');
		$class=$this->api->currentBranch->classes();


		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$class_model = $crud->add('Model_Class');
			// CreatNew Function call
			$class_model->createNew($form['name'],$form['section'],$form->api->currentBranch,$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($class);		

		if($g=$crud->grid){

			$g->addColumn('expander','subject','Associate Subject');
			$g->addColumn('expander','exam','Associate Exam');
			$g->addColumn('expander','fees','Associate Fees');

		}		
	}
}
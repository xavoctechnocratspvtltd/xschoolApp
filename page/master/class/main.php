<?php

class page_master_class_main extends Page {
	function page_index(){
		// parent::init();
	
		$crud=$this->add('xCRUD');
		$class=$this->add('Model_Class');//$this->api->currentBranch->classes();


		$crud->addHook('myupdate',function($crud,$form){
			if(!$form['branch_id'])
				throw $form->exception('Please specify Branch', 'ValidityCheck')->setField('branch_id');
			
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			
			// Do your stuff by getting $form data
			$class_model = $crud->add('Model_Class');
			// CreatNew Function call
			try{
				$crud->api->db->beginTransaction();
				$class_model->createNew($form['name'],$form['section'],$form->api->currentBranch,$form->getAllFields(),$form);
				
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			return true; // Always required
		});
		
		$crud->setModel($class);		

		if($g=$crud->grid){

			// $g->addPaginator(10);
			$g->addQuickSearch(array('branch','full_name'));
			$g->addTotals(array('no_of_students'));

			$g->addColumn('expander','subject','Associate Subject');
			$g->addColumn('expander','exam','Associate Exam');
			$g->addColumn('expander','fees','Associate Fees');
			$g->addColumn('expander','students','Students');

		}		
	}
}
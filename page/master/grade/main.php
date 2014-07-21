<?php
class page_master_grade_main extends Page {
	function init(){
		parent::init();

		$crud=$this->add('xCRUD');

		$grade=$this->add('Model_Grade');
		$grade->addcondition('session_id',$this->api->currentSession->id);
		
		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$grade_model = $crud->add('Model_Grade');
			// CreatNew Function call
			$grade_model->createNew($form['name'],$form['percentage'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($grade);		
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name','address','phone_no','principle_name','principle_contact_no'));

		}
	}
}
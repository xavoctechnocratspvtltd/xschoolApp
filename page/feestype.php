<?php

class page_feestype extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$fees_type=$this->add('Model_FeesAmountForStudentTypes');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$fees_type_model = $crud->add('Model_FeesType');
			// CreatNew Function call
			$fees_type_model->createNew($form['name'],$form['previouse_studentstype_id'],$form->getAllFields(),$form);
			return true; // Always required
		});

		if($crud->isEditing('add')){
			$crud->form->addField('line','amount','Default Amount');
		}	

		$crud->setModel($fees_type);		
	}
}
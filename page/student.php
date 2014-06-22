<?php

class page_student extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$student=$this->add('Model_Student');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$student_model = $crud->add('Model_Branch');
			// CreatNew Function call
			$student_model->createNew($form['name'],$form->getAllFields(),$form);
			return true; // Always required
		});
		
		$crud->setModel($student);		
	}
}
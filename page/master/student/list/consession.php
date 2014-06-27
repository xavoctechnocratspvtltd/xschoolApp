<?php
class page_master_student_list_consession extends Page {
	function init(){
		parent::init();


		$this->api->stickyGET('students_id');
		$this_student=$this->add('Model_Student')->load($_GET['students_id']);

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('line','consession');
		$form->addSubmit('Save');

		if($form->isSubmitted()){
			$this_student->consessionInFees($form['consession']);

			$form->js()->univ()->closeExpander()->execute();
		}

	}
}
<?php

class page_class_subject extends \Page {
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');

		$class=$this->add('Model_Class');
		$class->load($_GET['classes_id']);

		$branch=$this->add('Model_Branch');
		$branch->load($this->api->currentBranch);
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$subject_field=$form->addField('dropdown','subjects')->setEmptyText('Please Select')->validateNotNull();
		$subject_field->setModel($branch->subjects());
		$form->addSubmit('Add Subjects');
		$grid=$this->add('Grid');

		if($form->isSubmitted()){
			$subject=$this->add('Model_Subject');
			$subject->load($form['subjects']);

			$class->addSubject($subject);
			$grid->js()->reload()->execute();
		}

		if($_GET['remove_subject']){
			$class->removeSubject($this->add('Model_Subject')->load($_GET['remove_subject']));
			$grid->js()->reload()->execute();
		}

		$grid->setModel($class->allSubjects());

		$grid->addColumn('button','remove_subject');



	}
}
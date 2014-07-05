<?php

class page_class_exam extends Page {
	function init(){
		parent::init();
		$this->api->stickyGET('classes_id');

		$class=$this->add('Model_Class');
		$class->load($_GET['classes_id']);

		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$exam_field=$form->addField('dropdown','exam')->setEmptyText('Please Select')->validateNotNull();
		$exam_field->setModel('Exam');
		$form->addSubmit('Add Exam');
		$grid=$this->add('Grid');

		if($form->isSubmitted()){
			
			$exam=$this->add('Model_Exam');
			$exam->load($form['exam']);

			$class->addExam($exam);
			$grid->js()->reload()->execute();
		}

		if($_GET['remove_exam']){
			$class->removeExam($this->add('Model_Exam')->load($_GET['remove_exam']));
			$grid->js()->reload()->execute();
		}

		$grid->setModel($class->allExams());

		$grid->addColumn('button','remove_exam');
	}
}
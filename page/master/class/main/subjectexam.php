<?php

class page_master_class_main_subjectexam extends Page{
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');
		$this->api->stickyGET('exams_id');

		$exam=$this->add('Model_Exam');
		$exam->load($_GET['exams_id']);

		$class = $this->add('Model_Class')->load($_GET['classes_id']);

		$subjects = $class->allSubjects();

		foreach ($subjects as $subject) {
			$btn = $this->add('Button')->set($subjects['name']);
			if($subject_applied = $class->hasSubject($subjects)){
				$btn->addClass('btn btn-success');
			}else{
				$btn->addClass('btn btn-danger');		

			}

			if($btn->isClicked("Are you sure")){
				if($subject_applied){
					$class->removeSubject($subjects,$exams,$class);
				}else{
					$class->addSubject($subjects,$exams,$class);
				}
				$btn->js()->reload()->execute();
			}

		}

	}
}
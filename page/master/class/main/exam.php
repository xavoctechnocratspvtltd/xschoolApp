<?php

class page_master_class_main_exam extends Page{
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');

		$class = $this->add('Model_Class')->load($_GET['classes_id']);

		$exams = $this->add('Model_Exam');

		foreach ($exams as $exam) {
			$btn = $this->add('Button')->set($exams['name']);
			if($exam_applied = $class->hasExam($exams)){
				$btn->addClass('btn btn-success');
			}else{
				$btn->addClass('btn btn-danger');		

			}

			if($btn->isClicked("Are you sure")){
				if($exam_applied){
					$class->removeExam($exams);
				}else{
					$class->addExam($exams);
				}
				$btn->js()->reload()->execute();
			}

		}

	}
}
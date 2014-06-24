<?php

class page_master_class_main_subject extends Page{
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');

		$class = $this->add('Model_Class')->load($_GET['classes_id']);

		$subjects = $this->add('Model_Subject');

		foreach ($subjects as $subject) {
			$btn = $this->add('Button')->set($subjects['name']);
			if($subject_applied = $class->hasSubject($subjects)){
				$btn->addClass('btn btn-success');
			}else{
				$btn->addClass('btn btn-danger');		

			}

			if($btn->isClicked("Are you sure")){
				if($subject_applied){
					$class->removeSubject($subjects);
				}else{
					$class->addSubject($subjects);
				}
				$btn->js()->reload()->execute();
			}

		}

	}
}
<?php

class page_examsubject extends Page {
	function init(){
		parent::init();

		$this->api->stickyGET('classes_id');

		$class = $this->add('Model_Class')->load($_GET['classes_id']);
		$associateexam=$class->allExams();
		$grid=$this->add('Grid');
		$grid->setModel($associateexam);

	}
}
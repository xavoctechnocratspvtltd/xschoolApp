<?php

class page_master_class_main_students extends Page {
	function init(){
		parent::init();

		$class = $this->add('Model_Class')->load($_GET['classes_id']);

		$grid = $this->add('Grid');
		$grid->setModel($class->students());
	}
}
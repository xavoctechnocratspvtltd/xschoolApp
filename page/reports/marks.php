<?php

class page_reports_marks extends Page {
	function init(){
		parent::init();

		$class = $this->add('Model_Class')->load($_GET['class_id']);
		$term=null;
		if($_GET['term_id']){
			$term = $this->add('Model_Term')->load($_GET['term_id']);
		}

		$result = $class->getResult($term);

		$columns=array();

		foreach ($result as $junk) {
			$columns += array_keys($junk);
		}

		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";

		$grid = $this->add('Grid');
		$grid->setSource($result);

		foreach ($columns as $col) {
			$grid->addColumn('text',$col);
		}

		$grid->removeColumn('title');

	}
}
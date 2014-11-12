<?php

class page_restore extends Page{
	function init(){
		parent::init();

		$grid=$this->add('Grid');
		$student=$this->add('Model_CurrentStudent');
		$student->addCondition('is_left',true);
		$grid->setModel($student);
		$grid->add('misc/Export');
		if($_GET['restore']){
			$st=$this->add('Model_CurrentStudent');
			$st->load($_GET['restore']);
			$st->restore();
			$grid->js()->reload()->execute();
		}
		$grid->addColumn('button','restore');
	}
}
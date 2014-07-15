<?php
class page_marks extends Page {
	function init(){
		parent::init();
		$student_marks=$this->add('Model_Student_Marks');
		$count=$student_marks->count()->getOne();
		$this->add('H3')->set('Class-'.$count.$_GET['class'].'( Subject- '.$_GET['subject'].')'.'( Exam- '.$_GET['exam'].')');
		$this->js(true)->_selector('#header')->toggle();
		$this->js(true)->_selector('#footer')->toggle();
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(8);
		$grid=$col1->add('Grid');
		$grid->setModel($student_marks,array('student','marks'));



	}	
}
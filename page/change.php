<?php

class page_change extends Page{
	function init(){
		parent::init();

		$crud=$this->add('CRUD');
		$marks=$this->add('Model_Student_Marks');

		// $marks_join=$marks->join('students','student_id');
		// $s_marks_join=$marks_join->join('scholars','scholar_id');
		// $s_marks_join->addField('scholar_no');

		$crud->setModel($marks,array('student_id','class_id','scholar_no'),array('student','class','scholar_no'));

		if($crud->grid){
			$crud->grid->addPaginator(50);
			// $crud->grid->addQuickSearch(array('scholar_no'));
		}
	}
}
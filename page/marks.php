<?php
class page_marks extends Page {
	function init(){
		parent::init();



		$this->api->stickyGET('class');
		$this->api->stickyGET('subject');
		$this->api->stickyGET('exam');



		$student_marks=$this->add('Model_Student_Marks');
		$student_marks->addCondition('class_id',$_GET['class']);
		$student_marks->addCondition('subject_id',$_GET['subject']);
		$student_marks->addCondition('exam_id',$_GET['exam']);
		$student_marks->addCondition('session_id',$this->api->currentSession->id);

		$st_join=$student_marks->leftJoin('students','student_id');
		$sc_join=$st_join->leftJoin('scholars','scholar_id');
		$sc_join->addField('scholar_name','name');
		$st_join->addField('is_left');
		$student_marks->addCondition('is_left',false);

		$student_marks->setOrder('scholar_name');

		$count=$student_marks->count()->getOne();
		$this->add('H3')->set('Class-'.$_GET['class_name'].'( Subject- '.$_GET['subject_name'].')'.'( Exam- '.$_GET['exam_name'].')');
		$this->js(true)->_selector('#header')->toggle();
		$this->js(true)->_selector('#footer')->toggle();
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(8);
		$grid=$col1->add('Grid');
		$grid->setModel($student_marks,array('student','marks'));

		// $grid->addColumn('button','remove');

		// if($_GET['remove']){
		// 	$student_marks=$this->add('Model_Student_Marks');
		// 	$student_marks->load($_GET['remove']);
		// 	$student_marks->delete();
		// 	$grid->js()->reload()->execute();
		// }


	}	
}
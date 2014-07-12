<?php

class page_master_class_main_examsubject extends Page {
	function init(){
		parent::init();

		$this->api->stickyGET('classes_id');


		$class = $this->add('Model_Class')->load($_GET['classes_id']);
		$associateexam=$class->allExams();

		// $association_table_join = $associateexam->join('subjects_in_exam_class.exam_id','id');
		// $association_table_join->hasOne('Class','class_id');
		// // $association_table_join->hasOne('Session','session_id');

		// $association_table_join->addField('max_marks');
		// $association_table_join->addField('min_marks');

		// $associateexam->addCondition('class_id',$_GET['classes_id']);
		// $associateexam->addCondition('session_id',$this->api->currentSession->id);

		$grid=$this->add('Grid');
		$grid->setModel($associateexam);

		}
}
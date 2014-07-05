<?php

class Model_Marks extends Model_Table {
	var $table= "marks";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		$this->hasOne('Class','class_id');
		$this->hasOne('Exam','exam_id');
		$this->hasOne('Subject','subject_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
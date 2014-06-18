<?php
class Model_Student_Attendance extends Model_Table {
	var $table= "student_attendances";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
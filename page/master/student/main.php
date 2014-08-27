<?php

class page_master_student_main extends Page {
	function init(){
		parent::init();
		
		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('master_student_list','Students');
		$tabs->addTabUrl('master_student_attendance','Attendance');
		$tabs->addTabUrl('master_student_attendancereport','Attence Report');
		$tabs->addTabUrl('scholar_addtoclass','Add Scholar To Class');

		
	}
}
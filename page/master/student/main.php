<?php

class page_master_student_main extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');	
		$tabs=$this->add('Tabs');
		
		$tabs->addTabUrl('master_student_rollno','Allot Roll No');
		$tabs->addTabUrl('master_student_attendance','Attendance');
		$tabs->addTabUrl('master_student_attendancereport','Attence Report');
		
		

		
	}
}
<?php

class page_master extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('master_branch_main','Branch');
		$tabs->addTabUrl('master_session_main','Session');
		$tabs->addTabUrl('master_subject_main','Subject');
		$tabs->addTabUrl('master_term_main','Terms');
		$tabs->addTabUrl('master_exam_main','Exam');
		$tabs->addTabUrl('master_grade_main','Grade');
		$tabs->addTabUrl('master_student_type','Students Type');
		$tabs->addTabUrl('master_fees_main','Fees');
		$tabs->addTabUrl('master_class_main','Class');
		$tabs->addTabUrl('master_scholar_main','Scholar');
		$tabs->addTabUrl('master_student_list','Students');
		$tabs->addTabUrl('scholar_left','Mark Students Left');
		$tabs->addTabUrl('scholar_addtoclass','Add Scholar To Class');
		
		// $tabs->addTabUrl('master_fees_head','FeesHead'); //TODO DELETE
		$tabs->addTabUrl('master_user_main','Staff');
		$tabs->addTabUrl('master_user_ex','Ex-Staff');
		$tabs->addTabUrl('master_user_attendance','Staff Attendance');
		$tabs->addTabUrl('master_user_report','Staff Attendance Report');
		$tabs->addTabUrl('master_session_promote','Seesion Promote');

		
	}
}
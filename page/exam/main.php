<?php
class page_exam_main extends Page {
	function init(){
		parent::init();
		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('exam_manage','Manage Exam');
		$tab1=$tabs->addTabUrl('exam_marksassign','Feed Exam Data');
		$tab1=$tabs->addTabUrl('change','Change Class');
		
	}
}
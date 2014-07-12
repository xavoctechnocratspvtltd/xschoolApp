<?php
class page_library_student extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$issue=$cols->addColumn(4);
		$submit=$cols->addColumn(4);
		$recent_activity=$cols->addColumn(4);
		
		$issue->add('View_Student_Issue');
		$submit->add('View_Student_Submit');
		$recent_activity->add('View_Student_Recent');
		
			
	}
}
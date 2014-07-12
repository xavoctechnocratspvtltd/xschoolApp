<?php
class page_library_staff extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$issue=$cols->addColumn(4);
		$submit=$cols->addColumn(4);
		$recent_activity=$cols->addColumn(4);
		
		$issue->add('View_Staff_Issue');
		$submit->add('View_Staff_Submit');
		$recent_activity->add('View_Staff_Recent');
		
			
	}
}
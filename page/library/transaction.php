<?php

class page_library_transaction extends Page {
	function init(){
		parent::init();
	
		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('library_staff','For Staff');
		$tabs->addTabUrl('library_student','For Students');
	}
}
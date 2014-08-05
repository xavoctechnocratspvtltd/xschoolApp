<?php

class page_transport_main extends Page{
	function init(){
		parent::init();


		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabURL('transport_type','Vehicle Type');
		$tab1=$tabs->addTabURL('transport_vehicle','Vehicle');
		$tab1=$tabs->addTabURL('transport_assign','Vehicle Assign');
		$tab1=$tabs->addTabURL('transport_studentlist','Student List');
	}
}
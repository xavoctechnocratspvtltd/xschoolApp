<?php

class page_scholar_left extends Page{
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('left','Mark Left');
		$tab2=$tabs->addTabUrl('restore','Restore Left Student');

		


		// 
	}
}
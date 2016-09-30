<?php

class page_fees_main extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('fees_deposit','Desposit Fees');
		$tabs->addTabUrl('fees_change','Change Fees');

	}
}
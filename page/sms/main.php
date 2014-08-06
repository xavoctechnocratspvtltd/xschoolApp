<?php

class page_sms_main extends Page {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('sms_class','Send SMS Students');
		$tabs->addTabUrl('sms_general','Send SMS General');
	}
}
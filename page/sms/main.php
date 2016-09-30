<?php

class page_sms_main extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$tabs=$this->add('Tabs');
		$tabs->addTabUrl('sms_class','Send SMS Students');
		$tabs->addTabUrl('sms_general','Send SMS General');
		$tabs->addTabUrl('sms_defaulter','Send SMS Defaulter');
		$tabs->addTabUrl('sms_transportation','Send SMS For Transportation Status');
		$tabs->addTabUrl('sms_staff','Send SMS To Staff Members');
		$tabs->addTabUrl('sms_info','SMS Info');
	}
}
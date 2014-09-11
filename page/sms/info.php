<?php

class page_sms_info extends Page{
	function init(){
		parent::init();

		$grid=$this->add('Grid');
		$sms=$this->add('Model_Sms');
		$sms->addCondition('session_id',$this->api->currentSession->id);
		$sms->_dsql()->order('id','desc');

		$grid->setModel($sms);

		$grid->addpaginator(50);
	}
}
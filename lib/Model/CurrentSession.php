<?php


class Model_CurrentSession extends Model_Session{
	function init(){
		parent::init();

		$this->addCondition('session_id',$this->api->currentSession->id);
	}

}
<?php


class Model_CurrentStudent extends Model_Student{
	function init(){
		parent::init();

		$this->addCondition('session_id',$this->api->currentSession->id);

	}

}
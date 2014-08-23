<?php

class Model_Staff_Attendance extends Model_Table{
	var $table='staff_attendances';

	function init(){
		parent::init();
		$this->hasOne('Staff','staff_id')->sortable(true);
		$this->hasOne('Session','session_id')->defaultValue($this->api->currentBranch->id);
		$this->addField('attendence_on')->type('date')->defaultValue(date('Y-m-d'));
		$this->addField('is_present')->type('boolean')->defaultValue(false)->sortable(true);

		$this->setOrder('attendence_on','Desc');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function swapPresent(){
		$this['is_present']=!$this['is_present'];

		$log=$this->add('Model_Log');
		$log->createNew("Mark Staff Attendance");
		$log->save();
		$this->save();


	}

}
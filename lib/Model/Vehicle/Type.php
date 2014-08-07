<?php

class Model_Vehicle_Type extends Model_Table{
	public $table="vehicle_types";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('Vehicle','vehicle_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($name){
		if($this->loaded())
			throw $this->exception("You can not use loaded Model");
			
		$this['name']=$name;
		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("Vehicle Type Created");
		$log->save();
		return true;

	}

	function vehicle(){
		return $this->ref('Vehicle');
	}
}
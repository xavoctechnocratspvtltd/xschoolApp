<?php
class Model_Grade extends Model_Table {
	var $table= "grades";
	function init(){
		parent::init();

		$this->hasOne('Session','session_id')->defaultValue($this->api->currentSession->id);

		$this->addField('name');
		$this->addField('percentage')->caption('Above %');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($name,$percentage, $other_fields=array(),$form=null){
		$this['name']=$name;
		$this['percentage']=$percentage;

		unset($other_fields['name']);
		unset($other_fields['percentage']);

		foreach ($other_fields as $key => $value) {
			$this[$key] = $value;
		}

		$this->save();
	}
}
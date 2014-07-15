<?php
class Model_Grade extends Model_Table {
	var $table= "grades";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('max_marks');
		$this->addField('min_marks');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($name,$other_fields=array(),$form=null){
		
	}
}
<?php

class Model_Library_Transaction extends Model_Table{
	public $table="library_transactions";
	function init(){
		parent::init();
		

		$this->hasOne('Library_Item','item_id');
		$this->hasOne('Student','student_id');

		// $this->addField('name');
		$this->addField('issue_on')->type('date')->defaultValue($this->api->today);
		$this->addField('due_date')->type('date');
		$this->addField('submitted_on')->type('date');

		$this->add('dynamic_model/Controller_AutoCreator');

	}
}	
<?php

/**
* 
*/
class Model_ACL extends Model_Table{
	public $table="acl";
	function init(){
		parent::init();

		$this->hasOne('Staff','staff_id');
		$this->addField('page');
		$this->addField('is_allow')->type('boolean')->defaultValue(false)/*->display(array('grid'=>'grid/inline'))*/;

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
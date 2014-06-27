<?php
class Model_Temp extends Model_Table {
	var $table= "fees_temp";
	function init(){
		parent::init();

		$this->addField('scholar_no');
		$this->addField('receipt_no');
		$this->addField('amount');
		$this->addField('submit_date')->type('date');
		$this->add('dynamic_model/Controller_AutoCreator');
	}
}
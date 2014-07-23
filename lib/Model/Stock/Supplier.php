<?php

Class Model_Stock_Supplier extends Model_Table{
	public $table="stock_suppliers";
	function init(){
		parent::init();

		$this->addField('name');
		$this->addField('address');
		$this->addField('ph_no');
		$this->hasMany('Stock_Transaction','supplier_id');

		$this->add('dynamic_model/Controller_AutoCreator');


	}
}
<?php

Class Model_Stock_Transaction extends Model_Table{
	public $table="stock_transactions";
	function init(){
		parent::init();

		$this->hasOne('Stock_Item','item_id');
		$this->hasOne('Stock_Supplier','supplier_id');
		$this->addField('name');

		$this->add('dynamic_model/Controller_AutoCreator');


	}
}
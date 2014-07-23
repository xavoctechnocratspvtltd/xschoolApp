<?php

Class Model_Stock_Item extends Model_Table{
	public $table="stock_items";
	function init(){
		parent::init();

		$this->hasOne('Stock_Category','category_id');
		$this->addField('name');
		$this->hasMany('Stock_Transaction','item_id');

		$this->add('dynamic_model/Controller_AutoCreator');


	}
}
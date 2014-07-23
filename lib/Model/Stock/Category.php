<?php

Class Model_Stock_Category extends Model_Table{
	public $table="stock_category";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('Stock_Item','category_id');

		$this->add('dynamic_model/Controller_AutoCreator');


	}
}
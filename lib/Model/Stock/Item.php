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

	function createNew($name,$other_fields=array(),$form=array()){
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		$this['name']=$name;
		$this['category_id']=$other_fields['category_id'];
		$this->save();
			
	}

	function remove(){
		if(!$)
	}
}
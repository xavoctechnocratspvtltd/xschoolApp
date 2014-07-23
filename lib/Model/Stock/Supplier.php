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


	function createNew($name,$other_fields=array(),$form=array()){
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		$this['name']=$name;
		$this['address']=$other_fields['address'];
		$this['ph_no']=$other_fields['ph_no'];
		$this->save();
			
	}

	function remove(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");
		$this->delete();
	}

	function transaction(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");

		$this->ref('Stock_Transaction');

	}

	
}
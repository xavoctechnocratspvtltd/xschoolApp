<?php

Class Model_Stock_Item extends Model_Table{
	public $table="stock_items";
	function init(){
		parent::init();

		$this->hasOne('Stock_Category','category_id');
		$this->addField('name');
		$this->hasMany('Stock_Transaction','item_id');

		$this->addExpression('total_inward')->set(function($m,$q){
			return $m->refSQL('Stock_Transaction')->addCondition('type','Inward')->sum('qty');
		});

		$this->addExpression('total_consume')->set(function($m,$q){
			return $m->refSQL('Stock_Transaction')->addCondition('type','Consume')->sum('qty');
		});

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
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");
		$this->delete();
	}

	function transaction(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");

		$this->ref('Stock_Transaction');

	}

	function category_name(){
		if(!$this->loaded())
					throw $this->exception("Please call on loaded object");
			$this->ref('category_id')->get('name');	
	}

	function inward($supplier,$qty,$rate,$date,$remark,$session=null){

		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->inward($this,$supplier,$rate,$qty,$date,$remark,$session);

	}

	function consume($qty){

		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->consume($this,$qty);

	}

	function isAvailable($qty){
		if(!$this->loaded())
			throw $this->exception("Unable to determine the item");
		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->isAvailable($this,$qty);			
	}
	
}
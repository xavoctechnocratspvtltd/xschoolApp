<?php

Class Model_Stock_Category extends Model_Table{
	public $table="stock_category";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('Stock_Item','category_id');
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');


	}

	function beforeDelete(){
		if($this->ref('Stock_Item')->count()->getOne() > 0)
			throw $this->exception(' You can not delete, It contain Items');
	}

	function beforeSave(){
		$old_category=$this->add('Model_Category');
		$old_category->addCondition('name',$this['name']);

		if($this->loaded())
		$old_category->addCondition('id','<>',$this['id']);

		$old_category->tryLoadAny();
		if($old_category->loaded())
			throw $this->exception('This Category Allready Exist');


	}

	function createNew($name,$other_fields=array(),$form=array()){
		if($this->loaded())
			throw $this->exception("Please call on empty Model")->addMoreInfo();
		$this['name']=$name;
		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("Stoc Category Created");
		$log->save();
	}

	function remove(){
		if(!$this->loaded())
			throw $this->exception('Unable To dtermine the record');
		$this->delete();

		$log=$this->add('Model_Log');
		$log->createNew("stock Category Removed");
		$log->save();

	}

	function items(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");
		return $this->ref('Stock_Item');
	}
}
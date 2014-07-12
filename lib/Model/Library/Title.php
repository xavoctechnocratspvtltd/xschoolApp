<?php

class Model_Library_Title extends Model_Table{
	public $table="library_titles";
	function init(){
		parent::init();


		$this->hasOne('Library_Subjects','subject_id');
		
		$this->addField('name')->mandatory(true);

		$this->hasMany('Library_Item','title_id');
		$this->hasMany('Library_StockTransaction','title_id');

		$this->addHook('beforeDelete',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($title_name,$other_fields=array(),$form=null){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on create new item ");
		
		$this['name']=$title_name;
		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
	}

	function beforeDelete(){
		if($this->ref('Library_Item')->count()->getOne()>0)
			throw $this->exception(' You can Not delete, It contains Items and so Many Books...');		
	}

	function deleteForced(){
		$lt=$this->ref('Library_Item');
		foreach ($lt as $junk) {
			$lt->delete($force);
		}
		$this->delete();
	}

	function addItem($qty){
		
		// if(!$this->loaded())
		// 	throw $this->exception("You can not use UnLoaded Title Model for adding New Items ");
		$item=$this->add('Model_Library_Item');

		for($i=1; $i <= $qty; $i++){
			$item->createNew($this,'');
			$item->unload();
		}

	}

	function getAllItem(){

	}
	
}	
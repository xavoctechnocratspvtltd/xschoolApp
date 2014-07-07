<?php

class Model_Library_Category extends Model_Table{
	public $table="library_categories";
	function init(){
		parent::init();


		$this->hasOne('Branch','branch_id');
		
		$this->addField('name')->mandatory(true);
		$this->hasMany('Library_Title','category_id');

		$this->addHook('beforeDelete',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($category_name,$other_fields=array(),$form=null){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$category_name;

		$this['branch_id']=$this->api->currentBranch->id;

		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
	}

	function beforeDelete(){
		if($this->ref('Library_Title')->count()->getOne()>0)
			throw $this->exception(' You can Not delete, It contains Title and so Many Books...');
		
	}

	function deleteForced(){
		$lt=$this->ref('Library_Title');
		foreach ($lt as $junk) {
			$lt->delete($force);
		}
		$this->delete();
	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}

}		
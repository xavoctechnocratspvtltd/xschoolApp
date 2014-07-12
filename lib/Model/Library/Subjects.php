<?php

class Model_Library_Subjects extends Model_Table{
	public $table="library_subjects";
	function init(){
		parent::init();


		$this->addField('name')->mandatory(true);
		$this->hasMany('Library_Title','subject_id');

		$this->addHook('beforeDelete',$this);
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($category_name,$other_fields=array(),$form=null){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$category_name;

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

	

}		
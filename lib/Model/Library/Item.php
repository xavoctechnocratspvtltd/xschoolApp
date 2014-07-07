<?php

class Model_Library_Item extends Model_Table{
	public $table="library_items";
	function init(){
		parent::init();
		

		$this->hasOne('Library_Title','title_id');
		
		$this->addField('name')->caption('code')->mandatory(false)->display(array('grid'=>'grid/inline'));

		$this->hasMany('Library_Transaction','item_id');
		
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function beforeSave(){
		$older_items = $this->add('Model_Library_Item');
		$older_items->addCondition('id','<>',$this->id);
		$older_items->addCondition('name',$this['name']);
		$older_items->tryLoadAny();
		if($older_items->loaded()){
			throw $this->exception('Code Already Exists','ValidityCheck')->setField('name');
		}
	}
	
	function createNew($title, $code,$other_fields=array(),$form=null){		
		if(! ($title instanceof Model_Library_Title) or !$title->loaded())
			throw $this->exception("Title must be a loaded instance of Model_Library_Title");
			
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");

		$this['name']=$code;
		$this['title_id']=$title->id;
		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
	}

	function beforeDelete(){
		if($this->ref('Library_Transaction')->count()->getOne()>0 || $this->ref('Library_Transaction')->count()->getOne()>0)
			throw $this->exception(' You can Not delete, It contains even Transaction or Stock Transaction');
		
	}

	function deleteForced(){
		$lt=$this->ref('Library_Title');
		foreach ($lt as $junk) {
			$lt->delete($force);
		}
		$this->delete();
	}

}	
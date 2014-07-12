<?php

class Model_Library_StockTransaction extends Model_Table{
	public $table="library_stocktransactions";
	function init(){
		parent::init();

  
		$this->hasOne('Library_Title','title_id');
		$this->hasOne('Supplier','supplier_id');
			
		$this->addField('qty')->mandatory(true);
		$this->addField('narration')->type('text');
		$this->addField('date')->type('date')->defaultValue($this->api->today);

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($name,$other_fields=array(),$form=null){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		// $this['name']=$category_name;
		$this['qty']=$other_fields['qty'];
		$this['supplier_id']=$other_fields['supplier_id'];
		$this['narration']=$other_fields['narration'];
		$this['date']=$other_fields['date'];
		$this['branch_id']=$this->api->currentBranch->id;

		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}


		$this->save();
		$title=$this->add('Model_library_Title')->load($this['title_id']);
		$title->addItem($this['qty']);	

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
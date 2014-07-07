<?php

class Model_Supplier extends Model_Table{
	public $table="library_suppliers";
	function init(){
		parent::init();


		$this->addField('name')->caption('Supplier Name')->mandatory(true);
		$this->addField('company_name')->mandatory(true);
		$this->addField('company_address')->type('text')->mandatory(true);
		$this->addField('mobile_number')->type('int')->mandatory(true);
		$this->addField('email')->caption('Company E-mail');
		

		$this->hasMany('Library_StockTransaction','supplier_id');
		
		$this->addHook('beforeDelete',$this);		
		
		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($supplier_name,$other_fields=array(),$form=null){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$supplier_name;
		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
	}

	function beforeDelete(){
		if($this->ref('Library_StockTransaction')->count()->getOne()>0)
			throw $this->exception(' You can Not delete, this Supplier contains Many Stock Transaction');		
	}

	function deleteForced(){
		$st=$this->ref('Library_StockTransaction');
		foreach ($st as $junk) {
			$st->delete($force);
		}
		$this->delete();
	}
}	
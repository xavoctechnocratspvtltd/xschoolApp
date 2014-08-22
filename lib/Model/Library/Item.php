<?php

class Model_Library_Item extends Model_Table{
	public $table="library_items";
	function init(){
		parent::init();
		

		$this->hasOne('Library_Title','title_id');
		$this->hasOne('Branch','branch_id');
		
		$this->addField('name')->mandatory(false)->display(array('grid'=>'grid/inline'))->caption('Accession No');
		$this->addField('book_no');
		$this->addField('publishe_year');
		$this->addField('publisher');
		$this->addField('class_no');
		$this->addField('author');
		$this->addField('no_of_pages')->type('int');
		$this->addField('edition');
		$this->addField('volume');
		$this->addField('ISBN');
		$this->addField('bill_no');
		$this->addField('rate');
		$this->addField('supplier_name');
		$this->addField('is_issued')->type('boolean')->defaultValue(0);
		
		// $this->addExpression('full_name')->set('concat('.$this->ref('title_id')->get('name').','-',name)');
		$this->addExpression('full_name')->set(function($m,$q){
			return '(concat('.$q->getField('name').'," ",('.$m->refSQL('title_id')->fieldQuery('name')->render().')))';
		});

		// $this->addExpression('is_issued')->set(function($m,$q){
		// 	 return $m->refSQL('Library_Transaction')->_dsql()->where('submitted_on is not null')->del('fields')->field('count(*)');
		// 	 // return $m->refSQL('Library_Transaction')->setLimit(1)->setOrder('issue_on','desc')->_dsql()->del('fields')->field($q->expr('IF(submitted_on is null,1,0)'));
		// })->type('boolean');

		$this->hasMany('Library_Transaction','item_id');
		$this->addCondition('branch_id',$this->api->currentBranch->id);
		// $this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');

	}

	// function beforeSave(){

	// 	$older_items = $this->add('Model_Library_Item');
	// 	$older_items->addCondition('id','<>',$this->id);
	// 	$older_items->addCondition('name',$this['name']);
	// 	$older_items->tryLoadAny();
	// 	if($older_items->loaded()){
	// 		throw $this->exception('Code Already Exists','ValidityCheck')->setField('name');
	// 	}
	// }
	
	function createNew($title,$other_fields=array(),$form=null){		
		if(! ($title instanceof Model_Library_Title) )
			throw $this->exception("Title must be a loaded instance of Model_Library_Title");
			
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");

		$this['title_id']=$title->id;

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
		$log=$this->add('Model_Log');
		$log->createNew("Library Item Created");
		$log->save();
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

		$log=$this->add('Model_Log');
		$log->createNew("Library Item Delete");
		$log->save();
	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}

	function markIssued(){
		$this['is_issued']=true;
		$this->save();
	}

	function markSubmit(){
		$this['is_issued']=false;
		$this->save();
	}

}	
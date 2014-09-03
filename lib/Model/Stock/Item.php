<?php

Class Model_Stock_Item extends Model_Table{
	public $table="stock_items";
	function init(){
		parent::init();

		$this->hasOne('Stock_Category','category_id');
		$this->addField('name');
		$this->addField('is_issued')->type('boolean')->defaultValue(0);
		$this->addField('is_consumeable')->type('boolean')->defaultValue(0);
		$this->hasMany('Stock_Transaction','item_id');

		$this->addExpression('total_inward')->set(function($m,$q){
			return $m->refSQL('Stock_Transaction')->addCondition('type','Inward')->sum('qty');
		});

		$this->addExpression('total_consume')->set(function($m,$q){
			return $m->refSQL('Stock_Transaction')->addCondition('type','Consume')->sum('qty');
		});

		// $this->addExpression('total_issued')->set(function($m,$q){
		// 	return $m->refSQL('Stock_Transaction')->addCondition('type','Issue')->sum('qty');
		// });

		// $this->addExpression('total_submit')->set(function($m,$q){
		// 	return $m->refSQL('Stock_Transaction')->addCondition('type','Submit')->sum('qty');
		// });

		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function createNew($name,$other_fields=array(),$form=array()){
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		$this['name']=$name;
		$this['category_id']=$other_fields['category_id'];
		$this->save();
		$log=$this->add('Model_Log');
		$log->createNew("Stock Item Created");
		$log->save();
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

	function consume($qty,$staff){

		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->consume($this,$qty,$staff);

	}

	function issue($staff,$item,$qty,$narration,$branch=null,$session=null){

		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->issue($staff,$this,$qty,$narration);

	}

	function submit($staff,$item,$qty,$narration,$branch=null,$session=null){

		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->submit($staff,$this,$qty,$narration);

	}

	function getQty($as_on=null){
		if(!$as_on) $as_on = $this->api->now;

		$inward_tra = $this->add('Model_Stock_Transaction');
		$inward_tra->addCondition('item_id',$this->id);
		$inward_tra->addCondition('created_at','<',$this->api->nextDate($as_on));
		$inward_tra->addCondition('type','Inward');
		$inward_tra->addCondition('branch_id',$this->api->currentBranch->id);
		$inward_tra->addCondition('session_id',$this->api->currentSession->id);
		$inward_tra_qty = ($inward_tra->sum('qty')->getOne())?:0;

		$consume_tra = $this->add('Model_Stock_Transaction');
		$consume_tra->addCondition('item_id',$this->id);
		$consume_tra->addCondition('created_at','<',$this->api->nextDate($as_on));
		$consume_tra->addCondition('type','Consume');
		$consume_tra->addCondition('branch_id',$this->api->currentBranch->id);
		$consume_tra->addCondition('session_id',$this->api->currentSession->id);
		$consume_tra_qty = ($consume_tra->sum('qty')->getOne())?:0;


		$issue_tra = $this->add('Model_Stock_Transaction');
		$issue_tra->addCondition('item_id',$this->id);
		$issue_tra->addCondition('created_at','<',$this->api->nextDate($as_on));
		$issue_tra->addCondition('type','Issue');
		$issue_tra->addCondition('branch_id',$this->api->currentBranch->id);
		$issue_tra->addCondition('session_id',$this->api->currentSession->id);
		$issue_tra_qty = ($issue_tra->sum('qty')->getOne())?:0;

		$submit_tra = $this->add('Model_Stock_Transaction');
		$submit_tra->addCondition('item_id',$this->id);
		$submit_tra->addCondition('created_at','<',$this->api->nextDate($as_on));
		$submit_tra->addCondition('type','Submit');
		$submit_tra->addCondition('branch_id',$this->api->currentBranch->id);
		$submit_tra->addCondition('session_id',$this->api->currentSession->id);
		$submit_tra_qty = ($submit_tra->sum('qty')->getOne())?:0;

		return (($inward_tra_qty+$submit_tra_qty)-($consume_tra_qty+$issue_tra_qty));
	}



	function isAvailable($qty){
		if(!$this->loaded())
			throw $this->exception("Unable to determine the item");
		$transaction=$this->add('Model_Stock_Transaction');
		$transaction->isAvailable($this,$qty);			
	}


	function markIssued(){
		$this['is_issued']=true;
		$this->save();
	}

	function markSubmit(){
		$this['is_issued']=false;
		$this->save();
	}

	function canSubmit($from_staff,$qty,$on_date=null){
		if(!$on_date) $on_date= $this->api->today;

		return (($from_staff->issuedQty($this,$on_date)-$from_staff->submittedQty($this,$on_date)) >= $qty);

		
		
	}
	
}
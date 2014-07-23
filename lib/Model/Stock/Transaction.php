<?php

Class Model_Stock_Transaction extends Model_Table{
	public $table="stock_transactions";
	function init(){
		parent::init();

		$this->hasOne('Stock_Item','item_id');
		$this->hasOne('Session','session_id');
		$this->hasOne('Branch','branch_id');
		$this->hasOne('Stock_Supplier','supplier_id');
		$this->addField('qty');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('type')->enum(array('Inward','Consume'));
		$this->addField('remark')->type('text');
		$this->add('dynamic_model/Controller_AutoCreator');


	}

	function inward($item,$supplier,$rate,$qty,$date,$remark,$session){

		
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		if(!$item->loaded())
			throw $this->exception("please pass loaded object of Item Model");
		if(!$supplier->loaded())
			throw $this->exception("please pass loaded object of Supplier Model");
		if(!$item instanceof Model_Stock_Item)
			throw $this->exception("Object you passed on item spot is wrong, please pass item Model Object");
		if(!$supplier instanceof Model_Stock_Supplier)
			throw $this->exception("Object you passed on supplier spot is wrong, Please pass supplier Model Object");
		if(!$session) $session=$this->api->currentSession;

		$this['item_id']=$item->id;
		$this['supplier_id']=$supplier->id;
		$this['session_id']=$session->id;
		$this['qty']=$qty;
		$this['rate']=$rate;
		$this['type']='Inward';
		$this['remark']=$remark;
		$this['created_at']=$date;
		$this['branch_id']=$this->api->currentBranch->id;
		$this->save();
			
	}

	function consume($item,$qty){
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		if(!$item->loaded())
			throw $this->exception("please pass loaded object of Item Model");
		if(!$item instanceof Model_Stock_Item)
			throw $this->exception("Object you passed on item spot is wrong, please pass item Model Object");
		$this['item_id']=$item->id;
		$this['qty']=$qty;
		$this['type']='Consume';
		$this['branch_id']=$this->api->currentBranch->id;
		$this->save();

	}

	function remove(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");
		$this->delete();
	}

	function isAvailable($item,$qty,$session=null){
		if(!$session)
			$session=$this->api->currentSession;
		$this->addCondition('item_id',$item->id);
		$this->addCondition('session_id',$session->id);
		$this->tryLoadAny();
		if($this->loaded()){
			if($this->sum('qty')>$qty)
				return true;
			else
				return false;
				
			
		}

	}

	
}
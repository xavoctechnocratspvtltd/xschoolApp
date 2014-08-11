<?php

Class Model_Stock_Transaction extends Model_Table{
	public $table="stock_transactions";
	function init(){
		parent::init();

		$this->hasOne('Stock_Item','item_id');
		$this->hasOne('Session','session_id');
		$this->hasOne('Branch','branch_id');
		$this->hasOne('Stock_Supplier','supplier_id');
		$this->hasOne('Staff','staff_id');
		$this->addField('qty');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d H:i:s'));
		$this->addField('type')->enum(array('Inward','Consume','Issue','Submit'));
		$this->addField('remark')->type('text');
		$this->addField('issue_date')->type('date');
		$this->addField('submit_date')->type('date');
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

		$log=$this->add('Model_Log');
		$log->createNew("Stock Inward");
		$log->save();
			
	}

	function consume($item,$qty,$staff,$session=null,$branch=null){
		if($this->loaded())
			throw $this->exception("please call on loaded object");
		if(!$item->loaded())
			throw $this->exception("please pass loaded object of Item Model");
		if(!$item instanceof Model_Stock_Item)
			throw $this->exception("Object you passed on item spot is wrong, please pass item Model Object");
		if(!$branch)
			$branch=$this->api->currentBranch;
		if(!$session)
			$session=$this->api->currentSession;
		$this['item_id']=$item->id;
		$this['staff_id']=$staff->id;
		$this['branch_id']=$branch->id;
		$this['session_id']=$session->id;
		$this['qty']=$qty;
		$this['type']='Consume';
		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("stock  consume");
		$log->save();

	}

	function issue($staff,$item,$qty,$narration,$branch=null,$on_date=null,$form=null){


		if($this->loaded())
			throw $this->exception('Please call on empty Object');
		if(!($item instanceof Model_Stock_Item) and !$item->loaded() )
			throw $this->exception('Please loaded object of Item Model');
		
		if(!$on_date)
			$on_date=$this->api->today;
		if($item->getQty($on_date)<$qty)
			// $form->displayError('qty','This Item is not available in such qty');
			throw $this->exception('This Is not availeble in such Qty', 'ValidityCheck')->setField('qty');
		$this['item_id']=$item->id;
		$this['branch_id']=$this->api->currentBranch->id;
		$this['session_id']=$this->api->currentSession->id;
		$this['qty']=$qty;
		$this['type']='Issue';
		$this['issue_date']=$this->api->now;
		$this['staff_id']=$staff->id;
		$this['narration']=$narration;
		$this->save();

		$item=$this->add('Model_Stock_Item');
		$item->load($this['item_id']);
		$item->markIssued();

		$log=$this->add('Model_Log');
		$log->createNew("Stock Issue");
		$log->save();
	}


	function submit($staff,$item,$qty,$narration,$branch=null,$on_date=null){


		if($this->loaded())
			throw $this->exception('Please call on empty Object');
		if(!($item instanceof Model_Stock_Item) and !$item->loaded() )
			throw $this->exception('Please loaded object of Item Model');
		
		if(!$on_date)
			$on_date=$this->api->today;
		
		if(!$item->canSubmit($staff,$qty,$on_date))
			throw $this->exception('This Is not issued in such Qty', 'ValidityCheck')->setField('qty');
		$this['item_id']=$item->id;
		$this['branch_id']=$this->api->currentBranch->id;
		$this['session_id']=$this->api->currentSession->id;
		$this['qty']=$qty;
		$this['type']='Submit';
		$this['submit_date']=$this->api->now;
		$this['staff_id']=$staff->id;
		$this['narration']=$narration;
		$this->save();

		$item=$this->add('Model_Stock_Item');
		$item->load($this['item_id']);
		$item->markIssued();

		$log=$this->add('Model_Log');
		$log->createNew("Stock Submit");
		$log->save();
	}


	function remove(){
		if(!$this->loaded())
			throw $this->exception("Please call on loaded object");
		$this->delete();

		$log=$this->add('Model_Log');
		$log->createNew("Stock Removed");
		$log->save();
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
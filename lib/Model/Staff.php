<?php

class Model_Staff extends Model_Table{
	public $table="staffs";
	function init(){
		parent::init();

		$this->hasOne('Branch','branch_id')->mandatory(true);
		$this->addField('name')->mandatory(true);
		$this->addField('username');
		$this->addField('password')->type('password');
		$this->addField('fname')->caption('Father / Husband Name')->mandatory(true);
		$this->addField('is_application_user')->type('boolean')->defaultValue(false);
		$this->addField('dob');
		$this->addField('ph_no');
		$this->addField('mobile_no');
		$this->addField('address')->type('text');
		$this->addField('email');
		$this->addField('code');
		$this->hasMany('Library_transaction','staff_id');
		$this->hasMany('Staff_Attendance','staff_id');
		 $this->add('dynamic_model/Controller_AutoCreator');


	}

	function createNew($name,$username,$password,$other_fields=array(),$form=null){

		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$name;
		$this['username']=$username;
		$this['password']=$password;
		$this['branch_id']=$other_fields['branch_id'];
		$this['fname']=$other_fields['fname'];
		$this['dob']=$other_fields['dob'];
		$this['ph_no']=$other_fields['ph_no'];
		$this['mobile_no']=$other_fields['mobile_no'];
		$this['address']=$other_fields['address'];
		$this['email']=$other_fields['email'];
		$this['code']=$other_fields['code'];
		
		$this->save();
		
		$log=$this->add('Model_Log');
		$log->createNew("staff created");
		$log->save();	
	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}

	function issue($item){
		if(!$this->loaded())
			throw $this->exception("You can not use Loaded Model on issue ");
		if(!$item->loaded() or !$item instanceof Model_Library_Item)
			throw $this->exception("Please pass loaded object of Library_item ");
		$transaction=$this->add('Model_Library_Transaction');
		$transaction->issue($item,null,$this);
	}


	function issuedQty($item,$on_date=null){

		$transaction_stock_issue=$this->add('Model_Stock_Transaction');
		$transaction_stock_issue->addCondition('item_id',$item->id);
		$transaction_stock_issue->addCondition('staff_id',$this->id);
		$transaction_stock_issue->addCondition('created_at','<',$this->api->nextDate($on_date));
		$transaction_stock_issue->addCondition('type','Issue');
		$transaction_stock_issue_qty=$transaction_stock_issue->sum('qty')->getOne();
		// throw new Exception($transaction_stock_issue_qty);
		
		return $transaction_stock_issue_qty?:0;

	}


	function submittedQty($item,$on_date=null){

		$transaction_stock_submit=$this->add('Model_Stock_Transaction');
		$transaction_stock_submit->addCondition('staff_id',$this->id);
		$transaction_stock_submit->addCondition('item_id',$item->id);
		$transaction_stock_submit->addCondition('created_at','<',$this->api->nextDate($on_date));
		$transaction_stock_submit->addCondition('type','Submit');
		$transaction_stock_submit_qty=$transaction_stock_submit->sum('qty')->getOne();
		// throw new Exception($transaction_stock_submit_qty);
		 
		return $transaction_stock_submit_qty?:0;

	}


}
<?php

class Model_FeesTransaction extends Model_Table {
	var $table= "fees_transactions";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		$this->hasOne('StudentAppliedFees','student_applied_fees_id');
		$this->hasOne('FeesReceipt','fees_receipt_id');
		$this->hasOne('Branch','branch_id')->defaultValue($this->api->currentBranch->id);
		$this->hasOne('Session','session_id')->defaultValue($this->api->currentSession->id);
		$this->addField('amount')->type('money');
		$this->addField('by_consession')->type('boolean')->defaultValue(false);
		$this->addField('submitted_on')->type('date')->defaultValue($this->api->today);
		// $this->addCondition('session_id',$this->api->currentSession->id);
		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		// GET FEES NAME OF MY FEES APPLIED ID 
		// IF IT IS LATE FEES 
		// REMOVE APPLIED FEES
		
		
		$fees_name=$this->ref('student_applied_fees_id')->ref('fees_id')->get('name');
		if($fees_name=='Late Fees'){
			$this->ref('student_applied_fees_id')->delete();
		}

	}

	function createNew($receipt, $student_applied_fee,$amount){
		if($receipt !== null){
			$this['fees_receipt_id'] = $receipt->id;
			$this['by_consession']=false;
		}
		else{
			$this['fees_receipt_id'] = 0; // PayByConsession
			$this['by_consession']=true;
		}

		$this['student_applied_fees_id'] = $student_applied_fee->id;
		$this['amount'] = $amount;
		$this['student_id'] = $student_applied_fee['student_id'];
		$this->save();
		$log=$this->add('Model_Log');
		$log->createNew("fees transaction Created for receipt No".$receipt['name']);
		$log->save();
		return $this;
	}

	function deleteForced(){
		$my_receipt_id=$this['fees_receipt_id'];
		$my_amount = $this['amount'];

		$this->delete();


		$fees_receipt=$this->add('Model_FeesReceipt');
		$fees_receipt->load($my_receipt_id);

		if($fees_receipt->ref('FeesTransaction')->count()->getOne() == 0){
			// Last fee transaction is just deleted .. no receipt required now
			$fees_receipt->delete();
		}
		else{
			// Subtract the amount from fee receipt
			$fees_receipt['amount'] = $fees_receipt['amount'] - $my_amount;
			$fees_receipt->save();
		}

		$log=$this->add('Model_Log');
		$log->createNew("fees transaction deleted receipt No".$fees_receipt['name']);
		$log->save();
	}
}
<?php

class Model_FeesTransaction extends Model_Table {
	var $table= "fees_transactions";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		$this->hasOne('StudentAppliedFees','student_applied_fees_id');
		$this->hasOne('FeesReceipt','fees_receipt_id');
		$this->hasOne('Branch','branch_id')->defaultValue($this->api->currentBranch->id);
		$this->addField('amount')->type('money');
		$this->addField('by_consession')->type('boolean')->defaultValue(false);
		$this->addField('submitted_on')->type('date')->defaultValue($this->api->today);

		$this->add('dynamic_model/Controller_AutoCreator');
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
		$this->save();
		return $this;
	}

	function deleteForced(){
		$my_receipt_id=$this['fees_receipt_id'];

		$this->delete();


		$fees_receipt=$this->add('Model_FeesReceipt');
		$fees_receipt->load($my_receipt_id);

		if($fees_receipt->ref('FeesTransaction')->count()->getOne() == 0)
			$fees_receipt->delete();
	}
}
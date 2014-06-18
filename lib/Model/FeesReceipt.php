<?php

class Model_FeesReceipt extends Model_Table {
	var $table= "fees_receipts";
	function init(){
		parent::init();


		$this->hasOne('Branch','branch_id');
		$this->hasOne('Student','student_id');
		$this->addField('name')->caption('Receipt No');
		$this->addField('amount')->type('money');
		$this->addField('created_at')->type('date')->defaultValue($this->api->today);
		$this->hasMany('FeesTransaction','fees_receipt_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($student,$amount){

		$this['branch_id']=$this->api->currentBranch->id;
		$this['name']=$this->newReceiptNo();
		$this['student_id']=$student->id;
		$this['amount']=$amount;
		$this->save();

		$to_set_amount = $amount;
		$fees_for_this_student = $student->appliedFees()->setOrder('due_on,id');

		foreach ($fees_for_this_student as $fees_for_this_student_array) {
			$paid_againt_this_fees = $fees_for_this_student->paidAmount();
			$to_pay_for_this_fees = $fees_for_this_student['amount'] - $paid_againt_this_fees;

			if($to_pay_for_this_fees > $to_set_amount)
				$to_pay_for_this_fees = $to_set_amount;

			if($to_pay_for_this_fees==0) continue;

			// Actual payment made here ======================
			$fees_for_this_student->pay($to_pay_for_this_fees, $this);

			$to_set_amount = $to_set_amount - $to_pay_for_this_fees;

		}
	}


	function newReceiptNo($branch=null){
		if(!$branch) $branch=$this->api->currentBranch;
		
		$old_receipts=$this->add('Model_FeesReceipt');

		if( ! $this->api->getConfig('school/common_receipts'))
			$old_receipts->addCondition('branch_id',$branch->id);

		$max_receipt_no=$old_receipts->_dsql()->del('fields')->field('max(name)')->getOne();
		return $max_receipt_no+1;
	}
}
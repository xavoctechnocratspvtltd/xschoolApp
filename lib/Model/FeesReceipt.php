<?php

class Model_FeesReceipt extends Model_Table {
	var $table= "fees_receipts";
	function init(){
		parent::init();


		$this->hasOne('Branch','branch_id');
		$this->hasOne('Student','student_id');
		$this->addField('name')->caption('Receipt No');
		$this->addField('amount')->type('money');
		$this->addField('months')->type('text');
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
			$paid_against_this_fees = $fees_for_this_student->paidAmount();
			$to_pay_for_this_fees = $fees_for_this_student['amount'] - $paid_against_this_fees;

			if($to_pay_for_this_fees > $to_set_amount)
				$to_pay_for_this_fees = $to_set_amount;

			if($to_pay_for_this_fees==0) continue;

			// Actual payment made here ======================
			$fees_for_this_student->pay($to_pay_for_this_fees, $this);

			$to_set_amount = $to_set_amount - $to_pay_for_this_fees;

		}
	}

	function satisfiedMonths(){

		$month_print=array();
		$touched_months=array();
		$transactions_in_this_receipt = $this->ref('FeesTransaction');
		$transactions_in_this_receipt->join('student_fees_applied','student_applied_fees_id')
				->join('fees','fees_id')->addField('distribution');
		$transactions_in_this_receipt->setOrder('distribution desc,id');
		
		foreach ($transactions_in_this_receipt as $junk) {
			$transaction_aginst_fees_applied = $transactions_in_this_receipt->ref('student_applied_fees_id');
			$fees = $transaction_aginst_fees_applied->ref('fees_id');
			$is_yearly = ($fees['distribution'] == 'No');
			$in_month_year = date('M Y',strtotime($transaction_aginst_fees_applied['due_on']));
			if($is_yearly){
				$month_print[$fees['name']] = $transaction_aginst_fees_applied['amount'] - $transaction_aginst_fees_applied->paidAmountTill($this);
			}else{
				$month_print[$in_month_year] = 0;
				if(!in_array($transaction_aginst_fees_applied['due_on'], $touched_months))
					$touched_months[] = $transaction_aginst_fees_applied['due_on'];
			}

		}

		// All partial dues that are paid is covered in previous code, 
		// now cover
		// All dues that are either NOT PAID or PAID IN ANOTHER RECEIPT
		
		$fees_applied_in_required_months = $this->add('Model_StudentAppliedFees');
		$fees_applied_in_required_months->addCondition('due_on',$touched_months);
		$fees_applied_in_required_months->addCondition('student_id',$this['student_id']);

		foreach ($fees_applied_in_required_months as $junk) {
			$due_amount = $fees_applied_in_required_months->dueAmountAfter($this);
			if($due_amount){
				$month_print[$in_month_year] += $due_amount;
			}
		}

		// echo $in_month_year. " => " .$fees_applied_in_required_months->ref('fees_id')->get('name') . '=' . $month_print[$in_month_year] . '<br/>';
		return $month_print;



		$months_satisfied=array();
		$transactions_in_this_receipt = $this->ref('FeesTransaction');

		foreach ($transactions_in_this_receipt as $junk) {
			$transaction_for_fee_applied = $transactions_in_this_receipt->ref('student_applied_fees_id');
			$all_feeses_applied_in_same_month = $this->add('Model_StudentAppliedFees')
										->addCondition('due_on',$transaction_for_fee_applied['due_on'])
										->addCondition('student_id',$transaction_for_fee_applied['student_id']);
			
			$due_payment_in_month = false;
			foreach ($all_feeses_applied_in_same_month as $junk2) {
				$paid_till_receipt_date = $all_feeses_applied_in_same_month->paidAmount($this);
				// echo $all_feeses_applied_in_same_month['fees']. ' '. $all_feeses_applied_in_same_month['due_on']. ' '. $paid_till_receipt_date . ' DUE: '.($all_feeses_applied_in_same_month['amount']-$paid_till_receipt_date).'<br/>';
				if(($all_feeses_applied_in_same_month['amount']-$paid_till_receipt_date) > 0){
					$due_payment_in_month = true;
					break;
				}
			}

			if($due_payment_in_month==false){
				$month_to_add = date('M Y',strtotime($transaction_for_fee_applied['due_on']));
				if(!in_array($month_to_add, $months_satisfied))
					$months_satisfied[] = $month_to_add;
			}
		}

		return $months_satisfied;
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
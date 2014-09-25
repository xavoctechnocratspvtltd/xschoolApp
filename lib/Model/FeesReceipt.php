<?php

class Model_FeesReceipt extends Model_Table {
	var $table= "fees_receipts";
	function init(){
		parent::init();

		$this->hasOne('Branch','branch_id');
		$this->hasOne('Student','student_id');
		$this->hasOne('Session','session_id')->defaultValue($this->api->currentSession->id);
		$this->addField('name')->caption('Receipt No');
		$this->addExpression('tr_amount')->set(function($m,$q){
			return $m->refSQL('FeesTransaction')->sum('amount');
		})->type('money')->system(false);
		$this->addField('amount')->type('money');
		$this->addField('months')->type('text');
		$this->addField('mode')->enum(array('Cash','Cheque'));
		$this->addField('narration');
		$this->addField('created_at')->type('date')->defaultValue($this->api->today);
		$this->hasMany('FeesTransaction','fees_receipt_id');
		$this->hasMany('PaymentTransaction','fees_receipt_id');

		$this->addHook('beforeDelete',$this);

		$this->addCondition('session_id',$this->api->currentSession->id);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($student,$amount , $mode,$narration,$late_fees = 0){

		$this['branch_id']=$this->api->currentBranch->id;
		$this['name']=$this->newReceiptNo();
		$this['student_id']=$student->id;
		$this['amount']=$amount+$late_fees; // NOW AS EXPRESSION
		$this['mode']=$mode;
		$this['narration']=$narration;
		$this->save();
		
		if($late_fees){
			// apply late fees on student first
			$fees= $this->add('Model_Fees');
			$late_fees_applied = $this->add('Model_StudentAppliedFees');
			$late_fees_data = date('Y',strtotime($this->api->today)) . '-'. date('m',strtotime($this->api->today)) .'-'. $this->api->getConfig('school/fee_date');
			$late_fees_applied->addRow($student,$fees->loadLateFees(),$late_fees,$late_fees_data);
			// and pay the full amount immediately in the same receipt
			$late_fees_applied->pay($late_fees,$this);
		}

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

		if($to_set_amount>0){
			$this->delete();
			throw new Exception("Amount Remaining Not Saving Receipt");
		}
						

		$log=$this->add('Model_Log');
		$log->createNew("fees receipt Created receipt No".$this['name']);
		$log->save();
	}

	function beforeDelete(){

		$log=$this->add('Model_Log');
		$log->createNew("fees receipt fees delete receipt No".$this['name']);
		$log->save();
		foreach ($fees=$this->ref('FeesTransaction') as $junk) {
				$fees->delete();
		}

		foreach ($pay_tra=$this->ref('PaymentTransaction') as $junk) {
			$pay_tra->delete();
		}

	}

	function satisfiedMonths(){

		$month_print=array();
		$touched_months=array(0);
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
				if(!in_array($transaction_aginst_fees_applied['due_on'], $touched_months)){
					$touched_months[] = $transaction_aginst_fees_applied['due_on'];
					$last_touched_month = $transaction_aginst_fees_applied['due_on'];
				}
			}

		}

		// All partial dues that are paid is covered in previous code, 
		// now cover
		// All dues that are either NOT PAID or PAID IN ANOTHER RECEIPT
		
		$fees_applied_in_required_months = $this->add('Model_StudentAppliedFees');
		$fees_applied_in_required_months->addCondition('due_on',$touched_months);
		// $fees_applied_in_required_months->addCondition('due_on','<',$this->api->today);
		$fees_applied_in_required_months->addCondition('student_id',$this['student_id']);

		foreach ($fees_applied_in_required_months as $junk) {
			$due_amount = $fees_applied_in_required_months->dueAmountAfter($this,true); // $this (recipt) was passing in dueAmountAfter function that is now
			if($due_amount){
				$month_print[$in_month_year] += $due_amount;
			}
		}

		// All dues that are due till receipt date but even not touched in this receipt .. fully due
		$applied_fees_but_not_touched_till_receipt_date = $this->add('Model_StudentAppliedFees');
		$applied_fees_but_not_touched_till_receipt_date->addCondition('due_on','<=',$this['created_at']);
		$applied_fees_but_not_touched_till_receipt_date->addCondition('due_on','<>',$touched_months);
		$applied_fees_but_not_touched_till_receipt_date->addCondition('due_on','>=',$last_touched_month);
		$applied_fees_but_not_touched_till_receipt_date->addCondition('student_id',$this['student_id']);

		foreach ($applied_fees_but_not_touched_till_receipt_date as $junk) {
			$un_touched_month_year = date('M Y',strtotime($applied_fees_but_not_touched_till_receipt_date['due_on']));
			if(!isset($month_print[$un_touched_month_year])) $month_print[$un_touched_month_year] = 0;
			$month_print[$un_touched_month_year] += $applied_fees_but_not_touched_till_receipt_date['amount'];
		}



		// echo $in_month_year. " => " .$fees_applied_in_required_months->ref('fees_id')->get('name') . '=' . $month_print[$in_month_year] . '<br/>';
		return $month_print;
	}


	function newReceiptNo($branch=null){
		if(!$branch) $branch=$this->api->currentBranch;
		
		$old_receipts=$this->add('Model_FeesReceipt');

		if( ! $this->api->getConfig('school/common_receipts'))
			$old_receipts->addCondition('branch_id',$branch->id);

		$max_receipt_no=$old_receipts->_dsql()->del('fields')->field('max(name)')->getOne();
		return $max_receipt_no+1;

		$log=$this->add('Model_Log');
		$log->createNew("fees receipt genrated receipt No".$max_receipt_no+1);
		$log->save();
	}
}

<?php


class Model_StudentAppliedFees extends \Model_Table{
public $table="student_fees_applied";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		$this->hasOne('Fees','fees_id');
		$this->addField('amount');
		$this->addField('due_on')->type('date');

		$this->leftJoin('students','student_id')
			->leftJoin('scholars','scholar_id')
			->addField('name')
			;

		$this->addExpression('paid_amount')->set(function($m,$q){
			return $m->refSQL('FeesTransaction')->sum('amount');
		});

		$this->hasMany('FeesTransaction','student_applied_fees_id');

		$this->setOrder('due_on');

	    $this->add('dynamic_model/Controller_AutoCreator');

	}

	function paidAmount($via_receipt=null){
		$fee_trasactions = $this->ref('FeesTransaction');

		if($via_receipt !== null){
			$fee_trasactions->addCondition('submitted_on','<',$this->api->nextDate($via_receipt['created_at']));
			$fee_trasactions->addCondition('fees_receipt_id',$via_receipt->id);
		}

		// $amount = $fee_trasactions->_dsql()->del('fields')->field('sum(amount)')->getOne();
		$amount = $fee_trasactions->sum('amount')->getOne();
		return $amount;
	}

	function paidAmountTill($via_receipt=null){
		$fee_trasactions = $this->ref('FeesTransaction');

		if($via_receipt !== null){
			$fee_trasactions->addCondition('submitted_on','<',$this->api->nextDate($via_receipt['created_at']));
			$fee_trasactions->addCondition('fees_receipt_id','<=',$via_receipt->id);
		}

		// $amount = $fee_trasactions->_dsql()->del('fields')->field('sum(amount)')->getOne();
		$amount = $fee_trasactions->sum('amount')->getOne();
		return $amount;
	}

	function dueAmountAfter($after_receipt){
		return $this['amount'] - $this->paidAmountTill($after_receipt);
	}

	function pay($amount, $receipt){
		if($amount > ($this['amount'] - $this->paidAmount()) )
			throw $this->exception('Amount Exceeding required amount', 'ValidityCheck')->setField('amount');

		if(($this['amount'] - $this->paidAmount()) == 0)
			return; // All Fees Paid Already

		$newtransaction = $this->add('Model_FeesTransaction');
		$newtransaction->createNew($receipt,$this,$amount);

		// $due_amount = $this['amount'] - $this->paidAmount();
		
		// if($this->ref('fees_id')->get('distribution') == 'NO'){
		// 	$receipt['months']  = $receipt['months'] . ' ' . $this->ref('fees_id')->get('name'). ' ( '. $due_amount .'/- due),';
		// 	$receipt->save();
		// }

		

	}

	function submitFees($student){}

	function deleteForced(){

		foreach ($ft=$this->ref('FeesTransaction') as $junk) {
			$ft->deleteForced();
		}

		$this->delete();



	}

	function createNew($student, $fees){

		$start_date = $this->api->currentSession['start_date'];

		$start_date = date('Y',strtotime($start_date)) . '-'. date('m',strtotime($start_date)). '-'. $this->api->getConfig('school/fee_date');

		$count = ($fees['distribution']=='No')?1: $this->api->getConfig('school/emi'); // TODO CONFIG 8 MONTHS

		$first_fee_amount = $fee_amount = $student->type()->getAmount($fees);

		if($fee_amount==0) return; // Fee for this student type is 0

		if($fees['distribution'] == 'in_each_emi'){
			$include_emis_in_first_emi =  $this->api->getConfig('school/include_emis_in_first_emi');

			$count = $count - ($include_emis_in_first_emi - 1) ;
			$fee_amount = $fee_amount / $this->api->getConfig('school/emi');

			$first_fee_amount = $fee_amount * $include_emis_in_first_emi;

		}



		for($i=0; $i < $count; $i++){
			if($i==0)
				$add= '';
			else
				$add= "+$i months";

			$this['student_id'] = $student->id;
			$this['fees_id']=$fees->id;
			$this['amount']= ($i==0)?$first_fee_amount: $fee_amount;
			$this['due_on'] = date('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . $add));
			$this->saveAndUnload();
		}
	}

	function hasAssociation($student,$fees){
		if($this->loaded())
			throw $this->exception('Cannot work on loaded class');
		
		$this->addCondition('student_id',$student->id);
		$this->addCondition('fees_id',$fees->id);

		$this->tryLoadAny();

		if($this->loaded())
			return true;
		else
			return false;
	}

	function associations($student,$fees){
		if($this->loaded())
			throw $this->exception('Cannot work on loaded class');
		
		$this->addCondition('student_id',$student->id);
		$this->addCondition('fees_id',$fees->id);

		return $this;

	}

}
<?php

class page_correction extends Page {

	function page_receipts(){
		// Receipt amount == fee transaction amount sum == payment transaction amount ???

		
		// have receipt but not present in fee transaction fee receipt_id
			// put receipt Id in fee_transaction

		$receipt_model = $this->add('Model_FeesReceipt');

		$receipt_model->addExpression('tr_sum')->set(function ($m,$q){
			return $m->refSQL('FeesTransaction')->sum('amount');
		});

		$receipt_model->addExpression('pt_sum')->set(function ($m,$q){
			return $m->refSQL('PaymentTransaction')->sum('amount');
		});

		$receipt_model->_dsql()->having('tr_sum is null or pt_sum is null');

		foreach ($receipt_model as $mm) {
			// These receipt do not have any fee transaction ... wao 
			// Get all promising fee_trasactions first
			$fee_transactions = $this->add('Model_FeesTransaction');
			$fee_transactions->addCondition('submitted_on',$receipt_model['created_at']);
			$fee_transactions->addCondition('student_id',$receipt_model['student_id']);
			$fee_transactions->addCondition('fees_receipt_id',null);

			$fee_transactions->tryLoadAny();

			if(!$fee_transactions->loaded()){
				$this->add('View_Error')->setHTML('No Transaction found for reeipt ' . $receipt_model['name'] . ' <b>'.$receipt_model['amount'].'</b> on ' . $receipt_model['created_at']);
			}else{

				foreach ($fee_transactions as $junk) {
					// update receipt_id in fee_transactions model now
					$fee_transactions['fees_receipt_id'] = $receipt_model->id;
					// $fee_transactions->saveAndUnload();
				}

				$this->add('View_Info')->setHTML($receipt_model['name']. ' ' . $receipt_model['created_at'] . ' <b>'. $receipt_model['amount'] .'</b>');
				$g=$this->add('Grid');
				$g->setModel(clone $fee_transactions);
				$g->addTotals(array('amount'));
			}

			// if not found .. report on screen 
			// other wise update receipt_id
		}


		// NOW GET ALL THOSE FEES TRANSACTION THAT ARE NOT HAVING FEE RECEIPT AND THERE IS NO FEES SUBMITTED ON THAT DATE
		// MUST NOT BE by_consession
		// Mease the receipt is deleted.. so delete fee transactions as well ..
		// Double check by searching payment_transaction for same student in fee transaction.

		$this->add('H1')->set('Fee Transactions checking');
		$fees_transactions1 = $this->add('Model_FeesTransaction');
		$fees_transactions1->addCondition('by_consession',0);
		$fees_transactions1->addCondition('fees_receipt_id',null);
		$fees_transactions1->addExpression('students_receipt_on_same_date')->set(function($m,$q){
			$receipt = $m->add('Model_FeesReceipt',array('table_alias'=>'dc'));
			$receipt->addCondition('student_id',$q->getField('student_id'));
			$receipt->addCondition('created_at',$q->getField('submitted_on'));
			return $receipt->count();
		});

		$fees_transactions1->addCondition('students_receipt_on_same_date',0);
		$this->add('Grid')->setModel($fees_transactions1);

		foreach ($fees_transactions1 as $junk) {
			$fees_transactions1->delete();
		}







	}

	function page_receipt_diff(){

		$grid=$this->add('Grid');

		$fees_recept=$this->add('Model_FeesReceipt');
		// $fees_recept->_dsql()->having('amount','<>',$fees_recept->dsql()->expr('tr_amount'));

		$grid->setModel($fees_recept->debug());
		$grid->addOrder('created_at','desc');
		$grid->add('misc/Export');

		$grid->addPaginator(10);



		// $grid->addColumn('button','adjust_amount');
		// $grid->addColumn('button','adjust_transaction_amount');

		if($_GET['adjust_amount']){

			$old_transaction=$this->add('Model_FeesReceipt');
			$old_transaction->load($_GET['adjust_amount']);
			$old_transaction['amount']=$old_transaction['tr_amount'];
			$old_transaction->save();

			$grid->js()->reload()->execute();

		}
		// $grid->addPaginator(50);

		if($_GET['adjust_transaction_amount']){
			
		}
	}
}
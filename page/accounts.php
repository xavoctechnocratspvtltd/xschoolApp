<?php
class page_accounts extends Page {
	function init(){
		parent::init();

		
		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTab('Payment Received');
		$tab2=$tabs->addTab('Payment Paid');

		$payment_received=$this->add('Model_PaymentTransaction');
		$payment_received->addCondition('transaction_type','Income');
		$payment_received->setOrder('transaction_date','desc');
		$crud_received=$tab1->add('CRUD');

		$crud_received->setModel($payment_received,array('transaction_date','amount','mode','narration'),array('fees_receipt','transaction_date','amount','mode','narration'));

		if($crud_received->grid){
			$crud_received->grid->addPaginator(50);
			$crud_received->grid->addQuickSearch(array('transaction_date','amount'));

			$crud_received->grid->addMethod('format_editme',function($g,$field){
				if($g->model['fees_receipt']){
					$g->current_row_html[$field]='';
				}
			});

			$crud_received->grid->addFormatter('edit','editme');

		}



		$payment_paid=$this->add('Model_PaymentTransaction');
		$payment_paid->addCondition('transaction_type','Expense');
		$payment_paid->setOrder('transaction_date','desc');
		$crud_paid=$tab2->add('CRUD');
		if($crud_paid->grid){
			$crud_paid->grid->addPaginator(50);
			$crud_paid->grid->addQuickSearch(array('transaction_date','amount'));
		}
		$crud_paid->setModel($payment_paid,array('transaction_date','amount','mode','narration'));


	}
}
<?php
class page_accounts extends Page {
	function init(){
		parent::init();

		
		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTab('Payment Received');
		$tab2=$tabs->addTab('Payment Paid');

		$payment_received=$this->add('Model_PaymentTransaction');
		$payment_received->addCondition('transaction_type','Income');
		$payment_received->addCondition('branch_id',$this->api->currentBranch->id);
		$payment_received->setOrder('transaction_date','desc');
		$crud_received=$tab1->add('CRUD');

		$crud_received->setModel($payment_received,array('transaction_date','amount','mode','narration'),array('fees_receipt','transaction_date','amount','mode','narration'));

		if($_GET['print_received']){
			$this->js()->univ()->newWindow($this->api->url('printvoucher',array('transaction_id'=>$_GET['print_received'],'transaction_type'=>'Income')))->execute();
		}


		if($crud_received->grid){

			$crud_received->grid->addPaginator(50);
			$crud_received->grid->addQuickSearch(array('transaction_date','amount'));

			$crud_received->grid->addMethod('format_editme',function($g,$field){
				if($g->model['fees_receipt']){
					$g->current_row_html[$field]='';
				}
			});

			$crud_received->grid->addMethod('format_editme1',function($g,$field){
				if($g->model['fees_receipt']){
					$g->current_row_html[$field]='';
				}
			});

			$crud_received->grid->addColumn('button','print_received','print');
			$crud_received->grid->addFormatter('edit','editme');
			$crud_received->grid->addFormatter('print_received','editme1');

		}



		$payment_paid=$this->add('Model_PaymentTransaction');
		$payment_paid->addCondition('transaction_type','Expense');
		$payment_paid->addCondition('branch_id',$this->api->currentBranch->id);
		$payment_paid->setOrder('transaction_date','desc');
		$crud_paid=$tab2->add('CRUD');
		if($_GET['print_paid']){
			$this->js()->univ()->newWindow($this->api->url('printvoucher',array('transaction_id'=>$_GET['print_paid'],'transaction_type'=>'Expense')))->execute();
		}
		$crud_paid->setModel($payment_paid,array('transaction_date','amount','mode','narration'));

		if($crud_paid->grid){
			$crud_paid->grid->addPaginator(50);
			$crud_paid->grid->addQuickSearch(array('transaction_date','amount'));
			$crud_paid->grid->addColumn('button','print_paid','print');
			// $crud_received->grid->addFormatter('print_received','editme1');
		}

	}
}
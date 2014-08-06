<?php

class page_correction extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');

		$fees_recept=$this->add('Model_FeesReceipt');
		$fees_recept->_dsql()->having('amount','<>',$fees_recept->dsql()->expr('tr_amount'));

		$grid->setModel($fees_recept->debug());

		$grid->addColumn('button','adjust_amount');
		$grid->addColumn('button','adjust_transaction_amount');

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
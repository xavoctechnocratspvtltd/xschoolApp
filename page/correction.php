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

		// $grid->addPaginator(50);

	}
}
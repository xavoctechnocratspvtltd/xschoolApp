	<?php

class page_temp extends Page {
	function init(){
		parent::init();

		$fee_transactions = $this->add('Model_FeesTransaction');

		// foreach ($fee_transactions as $junk) {
		// 	$fee_transactions['student_id'] = $fee_transactions->ref('fees_receipt_id')->get('student_id');
		// 	$fee_transactions->save();
		// }

		$crud=$this->add('CRUD');
		$crud->setModel($fee_transactions);
		if($crud->grid)
			$crud->grid->addPaginator(50);
	}
}
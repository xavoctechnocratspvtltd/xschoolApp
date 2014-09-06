<?php

class page_library_submit extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');

		$transaction=$this->add('Model_Library_Transaction');
		$transaction->addCondition('branch_id',$this->api->currentBranch->id);
		$transaction->addCondition('session_id',$this->api->currentSession->id);
		$transaction->addCondition('submitted_on',null);

		$grid->setModel($transaction,array('item','student','staff','issue_on'));


		
	}

}
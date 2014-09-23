<?php

class page_library_submit extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');

		$transaction=$this->add('Model_Library_Transaction');
		$transaction->addCondition('branch_id',$this->api->currentBranch->id);
		$transaction->addCondition('session_id',$this->api->currentSession->id);
		$transaction->addCondition('submitted_on',null);
		$transaction_item_j=$transaction->join('library_items','item_id');
		$transaction_item_j->addField('is_issued');
		$transaction->addCondition('is_issued',1);
		$transaction->addExpression('class_name')->set(function($m,$q){
			$student_join=$m->leftJoin('students','student_id');
			$student_join->hasOne('Class','class_id');
        	return $m->refSQL('class_id')->fieldQuery('full_name');
			});		

		$grid->setModel($transaction->debug(),array('item','student','class_name','staff','issue_on'));


		
	}

}
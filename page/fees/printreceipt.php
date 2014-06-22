<?php

class page_fees_printreceipt extends Page {
	
	function init(){
		parent::init();

		$receipt=$this->add('Model_FeesReceipt');
		$receipt->load($_GET['receipt_id']);

		$this->add('View_Student_PrintReceipt')->setModel($receipt);	

		// $this->api->destroy('logo');
		// 
		$this->js(true)->_selector('#header')->hide();	
		$this->js(true)->_selector('#footer')->hide();	
	}
}
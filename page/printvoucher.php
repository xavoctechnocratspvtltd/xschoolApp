<?php

class page_printvoucher extends Page {
	
	function init(){
		parent::init();

		$transaction=$this->add('Model_PaymentTransaction');
		$transaction->addCondition('transaction_type',$_GET['transaction_type']);
		$transaction->load($_GET['transaction_id']);


		$this->add('View_Voucher')->setModel($transaction);	

		// $this->api->destroy('logo');
		// 
		$this->js(true)->_selector('#header')->hide();	
		$this->js(true)->_selector('#footer')->hide();	
	}
}
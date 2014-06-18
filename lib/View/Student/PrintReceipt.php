<?php

class View_Student_PrintReceipt extends View {
	
	function setModel($receipt){

		parent::setModel($receipt);

		$this->template->set('school_name',$this->api->getConfig('school/name'));
		$this->template->set('sub1',$this->api->getConfig('school/sub1'));
		$this->template->set('sub2',$this->api->getConfig('school/sub2'));
	}

	function defaultTemplate(){
		return array('view/receiptprint');
	}
}
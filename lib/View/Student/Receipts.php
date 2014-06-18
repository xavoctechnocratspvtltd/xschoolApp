<?php

class View_Student_Receipts extends \View {
	public $student;
	function init(){
		parent::init();

		$receipt=$this->student->feesReceipts();

		$grid=$this->add('Grid');

		$grid->setModel($receipt);

		$grid->addColumn('button','print');

		if($_GET['print']){
			$this->js()->univ()->newWindow($this->api->url('fees_printreceipt',array('receipt_id'=>$_GET['print'])))->execute();
		}

	}
}
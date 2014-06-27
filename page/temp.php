<?php

class page_temp extends Page {
	function init(){
		parent::init();

		$fees_temp=$this->add('Model_Temp');
		foreach ($fees_temp as $junk) {
			$scholar=$this->add('Model_Scholar');
			$scholar->loadBy('scholar_no',$fees_temp['scholar_no']);

			$receipt=$scholar->student()->submitFees($fees_temp['amount']);
			$receipt['name']=$fees_temp['receipt_no'];
			$receipt->save();

		}

	}
}
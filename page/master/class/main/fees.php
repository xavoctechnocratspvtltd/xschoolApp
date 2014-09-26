<?php

class page_master_class_main_fees extends Page {
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');

		$class = $this->add('Model_Class')->load($_GET['classes_id']);

		$feeses = $this->add('Model_Fees');

		foreach ($feeses as $fee) {
			$btn = $this->add('Button')->set($feeses['name']);
			if($fee_applied = $class->hasFees($feeses)){
				$btn->addClass('btn btn-success');
			}else{
				$btn->addClass('btn btn-danger');		

			}

			if($btn->isClicked("Are you sure")){
				if($fee_applied){
					$msg = $class->removeFees($feeses);
				}else{
					$msg = $class->addFees($feeses);
				}
				$btn->js(null,$btn->js()->univ()->errorMessage($msg))->reload()->execute();
			}

		}

	}
}
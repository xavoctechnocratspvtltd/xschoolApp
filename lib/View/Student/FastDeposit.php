<?php

class View_Student_FastDeposit extends View{

	public $student=null;
	
	function init(){
		parent::init();

		$student = $this->student;

		if(! $student instanceof Model_Student)
			throw $this->exception('View_Student_FastDeposit must have loaded student passed');

		
		$form = $this->add('Form');
		$form->addField('line','amount');
		$form->addField('line','late_fees');
		$form->addField('dropdown','mode')->setValueList(array('Cash'=>'Cash','Cheque'=>'Cheque'));
		$form->addField('text','narration');
		$form->addSubmit('Submit Fees');

		if($form->isSubmitted()){
			if(($due_amount = $student->getDueFeesAmount()) < $form['amount'])
				$form->displayError('amount','Amount cannot exceed than '. $due_amount.'/-' );
			$student->submitFees($form['amount'],$form['mode'],$form['narration'],$form['late_fees']);
			$form->js()->_selector('.fee_submit_block')->trigger('reload_me')->execute();
		}
	}
}
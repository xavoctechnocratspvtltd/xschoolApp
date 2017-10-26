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
			$due_amount = $student->getDueFeesAmount();
			if($due_amount < $form['amount'])
				$form->displayError('amount','Amount cannot exceed than '. $due_amount.'/-' );
			try{
				$this->api->db->beginTransaction();
				$student->submitFees($form['amount'],$form['mode'],$form['narration'],$form['late_fees']);
				$message="Dear Parent We received fee of <".$student['name']."> <".$student['scholar_no'].">, of Rs. <". ($form['amount'] + $form['late_fees']).">. Regards Principal";
				$sms=$this->add('Model_Sms');
				$sms->sendMessage($message,$student['phone_no'].',9116609131',null);
				$this->api->db->commit();
				
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
			$form->js()->_selector('.fee_submit_block')->trigger('reload_me')->execute();
		}
	}
}
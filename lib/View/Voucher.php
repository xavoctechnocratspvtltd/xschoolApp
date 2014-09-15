<?php

class View_Voucher extends View {
	
	function setModel($transaction){

		parent::setModel($transaction);

		$this->template->set('school_name',$this->api->getConfig('school/name'));
		$this->template->set('date',date('d-M-Y',strtotime($transaction['transaction_date'])));

		if($transaction['transaction_type']=='Income'){
		$this->template->set('val1',$transaction['narration']);
		$this->template->set('val2',$transaction['mode']);
		$this->template->set('type','RECEIPT VOUCHER');
		$this->template->set('lable_1','Credit');
		$this->template->set('lable_2','DEBIT');
		
		}else{
		$this->template->set('val1',$transaction['narration']);
		$this->template->set('val2',$transaction['mode']);
		$this->template->set('type','PAYMENT VOUCHER');
		$this->template->set('lable_1','Debit');
		$this->template->set('lable_2','Credit');

		}
	}

	function defaultTemplate(){
		return array('view/voucher');
	}
}
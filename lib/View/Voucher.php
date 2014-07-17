<?php

class View_Voucher extends View {
	
	function setModel($transaction){

		parent::setModel($transaction);

		$this->template->set('school_name',$this->api->getConfig('school/name'));
		$this->template->set('date',date('d-M-Y',strtotime($transaction['transaction_date'])));

		if($transaction['transaction_type']=='Income'){
		$this->template->set('debit',$transaction['narration']);
		$this->template->set('credit',$transaction['mode']);

		}else{
		$this->template->set('credit',$transaction['narration']);
		$this->template->set('debit',$transaction['mode']);

		}
	}

	function defaultTemplate(){
		return array('view/voucher');
	}
}
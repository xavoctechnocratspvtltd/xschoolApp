<?php
class Model_PaymentTransaction extends Model_Table {
	var $table= "payment_transactions";
	function init(){
		parent::init();

		$this->hasOne('FeesReceipt','fees_receipt_id');
		
		$this->addField('amount')->caption('Amount');
		$this->addField('transaction_date')->type('datetime')->defaultValue($this->api->now)->caption('Date');
		$this->addField('transaction_type')->enum(array('Expense','Income'));
		$this->addField('narration');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew($fees_receipt_id=null,$amount,$transaction_type,$narration="xyz"){

		$this['fees_receipt_id']=$fees_receipt_id;
		$this['transaction_type']=$transaction_type;
		$this['amount']=$amount;
		$this['narration']=$narration;
		$this->save();
	}

	function beforeDelete(){
		if($this['fees_receipt_id']){
			$this->ref('fees_receipt_id')->delete();
		}
	}


}
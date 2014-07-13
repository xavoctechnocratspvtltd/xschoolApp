<?php
class Model_PaymentTransaction extends Model_Table {
	var $table= "payment_transactions";
	function init(){
		parent::init();

		$this->hasOne('FeesReceipt','fees_receipt_id');
		$this->hasOne('Branch','branch_id')->display(array('grid'=>'grid/inline'));
		
		$this->addField('amount')->caption('Amount')->mandatory(true);
		$this->addField('transaction_date')->type('datetime')->defaultValue($this->api->now)->caption('Date')->mandatory(true);
		$this->addField('transaction_type')->enum(array('Expense','Income'));
		$this->addField('narration')->mandatory(true);
		$this->addField('mode')->enum(array('Cash','Cheque'))->display(array('grid'=>'grid/inline'))->mandatory(true);

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew($amount,$transaction_type,$mode,$narration,$fees_receipt_id=null){

		$this['fees_receipt_id']=$fees_receipt_id;
		$this['transaction_type']=$transaction_type;
		$this['amount']=$amount;
		$this['mode']=$mode;
		$this['narration']=$narration;
		$this->save();
	}

	function beforeDelete(){
		if($this['fees_receipt_id']){
			$this->ref('fees_receipt_id')->delete();
		}
	}


}
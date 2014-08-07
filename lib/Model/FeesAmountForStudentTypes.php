<?php

class Model_FeesAmountForStudentTypes extends Model_Table {
	var $table= "fees_amount_for_student_types";

	function init(){
		parent::init();

		$this->hasOne('Fees','fees_id');
		$this->hasOne('StudentType','studenttype_id');
		$this->hasOne('Session','session_id');

		$this->addField('amount')->display(array('grid'=>'grid/inline'));

		// $this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew($fees_id,$studenttype,$session=null, $amount=0){
		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNew");

		if(!$session) $session = $this->api->currentSession;

		$this['fees_id']=$fees_id;
		$this['studenttype_id']=($studenttype instanceof Model_StudentType)? $studenttype->id : $studenttype;
		$this['session_id']=$session->id;
		$this['amount']=($amount==0)? $this->ref('fees_id')->get('default_amount'): $amount;

		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("apply fees on student type");
		$log->save();

	}
}
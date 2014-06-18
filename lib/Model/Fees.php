<?php


class Model_Fees extends Model_Table {
public $table="fees";
	function init(){
		parent::init();

		// $this->hasOne('FeesHead','feeshead_id'); //TODO DELETE
		// $this->addField('club_additional_emis')->type('number')->defaultValue('0');
		$this->addField('name')->mandatory(true);
		$this->addField('default_amount');

		$this->addField('distribution')->setValueList(array('No'=>'NO','in_each_emi'=>'IN EACH EMI'))->mandatory(true);

		// $this->addField('optional_to_student')->type('boolean')->defaultValue(false);
		

		$this->hasMany('FeesAmountForStudentTypes','fees_id');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($fees_name, $default_amount, $distribution, $other_fields=array(),$form){

		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$fees_name;
		$this['default_amount']=$default_amount;
		$this['distribution']=$distribution;

		$this->save();
		
		foreach($st=$this->add('Model_StudentType') as $junk){
			$fees_amount=$this->add('Model_FeesAmountForStudentTypes');
			$fees_amount->createNew($this->id,$st->id,null,$default_amount);
		}
	}

	function deleteForced(){
			$fafst=$this->ref('FeesAmountForStudentTypes');
			foreach ($fafst as $junk) {
				$fafst->delete($force);
			}
		
	}

	function beforeDelete(){

		$fafst=$this->ref('FeesAmountForStudentTypes');
		foreach ($fafst as $junk) {
			$fafst->delete();
		}
	}

	function getAmount($studenttype){
		$fees_amount=$this->add('Model_FeesAmountForStudentTypes');
		$fees_amount->addCondition('studenttype_id',$studenttype->id);
		$fees_amount->addCondition('fees_id',$this->id);
		$fees_amount->tryLoadAny();

		return $fees_amount['amount'];
	}


	function filterByID($fees_ids_array){
		$this->addCondition('id',$fees_ids_array);
		return $this;
	}



	function allClass($session=null){
		if(!$this->loaded()) throw $this->exception('Fees Must be loaded to get Class');
		if(!$session) $session = $this->api->currentSession;

		$classes = $this->add('Model_FeesInAClass');
		$classes->associatedClassWithFees($this,$session);
		return $classes;
	}

	function amountForStudentTypes($session=null){
		if(!$session)  $session = $this->api->currentSession;
		if(!$this->loaded()) throw $this->exception('Model Must be loaded before getting amounts')->addMoreInfo('Model',get_class($this));
	
		$fee_amount_for_student_types = $this->add('Model_FeesAmountForStudentTypes');
		$fee_amount_for_student_types->addCondition('fees_id',$this->id);
		$fee_amount_for_student_types->addCondition('session_id',$session->id);

		return $fee_amount_for_student_types;
	}

}
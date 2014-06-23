<?php

class Model_StudentType extends Model_Table {
	var $table= "student_types";
	function init() {
		parent::init();

		$this->hasOne( 'StudentTypePrev', 'previouse_studenttype_id' );
		$this->addField( 'name' )->mandatory(true);
		$this->hasMany( 'FeesAmountForStudentTypes', 'studenttype_id' );
		$this->hasMany( 'StudentType', 'previouse_studenttype_id', null, 'LastYearStudentType' );
			// $this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew( $studenttype_name, $previouse_studenttype_id=null, $other_field=array(), $form=null ) {
		if ( $this->loaded() )
			throw $this->exception( "You can not use Loaded Model on createNew" );

		$this['name']=$studenttype_name;
		$this['previouse_studenttype_id']=$previouse_studenttype_id;
		$this['is_default']=$other_field['is_default'];
		$this->save();

		$default_amount = 0;
			if($other_field['amount']) $default_amount = $other_field['amount'];

		foreach($fe=$this->add('Model_Fees') as $junk){
			$fees_amount=$this->add('Model_FeesAmountForStudentTypes');
			$fees_amount->createNew($fe->id,$this->id);
		}
	}


	function getAmount($fees){
		$fees_amount=$this->add('Model_FeesAmountForStudentTypes');
		$fees_amount->addCondition('studenttype_id',$this->id);
		$fees_amount->addCondition('fees_id',$fees->id);
		$fees_amount->tryLoadAny();

		return $fees_amount['amount'];
	}

}

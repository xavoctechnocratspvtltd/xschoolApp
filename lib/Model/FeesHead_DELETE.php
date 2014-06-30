<?php


class Model_FeesHead extends \Model_Table{
	var $table="fee_heads";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('Fees','feehead_id');
		// $this->addHook('beforeDelete',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function delete($force=fales){
		$fees=$this->add('Model_Fees');

		if($force){
			foreach ($fees as $junk) {
				$fees->delete($force);
			}

			if($fees->count()->getOne()> 0)
				throw $this->exception(' You can not delete, It contain fees');
			$fees->delete();
		}
	}
    
}
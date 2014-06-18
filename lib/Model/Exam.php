<?php

class Model_Exam extends Model_Table{
public $table="exams";
	function init(){
		parent::init();

		
		$this->addField('name');
		$this->hasMany('ExamInAClass','exam_id');
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($exam_name,$all_fields,$form){

		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$exam_name;
		$this->save();
			
	}

	function deleteForced(){
		$eic=$this->ref('ExamInAClass');

		foreach ($eic as $junk){
			$eic->deleteForced();		
		}
	}

	function getAllExam($exam_ids_array){

		$this->addCondition('id',$exam_ids_array);
		return $this;
	}

	function beforeDelete(){
		if($this->ref('ExamInAClass')->count()->getOne()> 0)
				throw $this->exception(' You can not delete, It is added in class');

	}

}
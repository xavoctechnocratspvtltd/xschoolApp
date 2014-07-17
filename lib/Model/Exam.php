<?php

class Model_Exam extends Model_Table{
public $table="exams";
	function init(){
		parent::init();

		$this->hasOne('Term','term_id');
		$this->addField('name')->mandatory(true);
		$this->hasMany('ExamInAClass','exam_id');
		$this->hasMany('SubjectInExamClass','exam_id');
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function createNew($exam_name,$all_fields=array(),$form=null){

		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$exam_name;
		$this['term_id']=$all_fields['term_id'];
		$this->save();
			
	}

	function deleteForced(){
		$eic=$this->ref('ExamInAClass');

		foreach ($eic as $junk){
			$eic->deleteForced();		
		}
	}

	function filterByID($exam_ids_array){

		$this->addCondition('id',$exam_ids_array);
		return $this;
	}

	function beforeDelete(){
		if($this->ref('ExamInAClass')->count()->getOne()> 0)
				throw $this->exception(' You can not delete, It is added in class');

	}

	function filterByIDs($exam_id_array){
		$this->addCondition('id',$exam_id_array);
		return $this;
	}

	function isExist($exam){
		$this->addCondition($this['name'],$exam['name']);
		$this->tryLoadAny();
		if($this->loaded())
			return $this;
		else
			return false;

	}


}
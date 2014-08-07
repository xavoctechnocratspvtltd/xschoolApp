<?php
class Model_Term extends Model_Table {
	var $table= "terms";
	function init(){
		parent::init();

		$this->addField('name');
		$this->hasMany('Exam','term_id');
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		if($this->ref('Exam')->count()->getOne() > 0)
			throw $this->exception(' Can not delete Term contains subjects');
	}

	function createNew($name,$other_fields=array(),$form=null){

		if($this->loaded())
			throw $this->exception('Can not use loaded object of term on createNew function');
		$this['name']=$name;
		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("term created");
		$log->save();

	}

	function remove(){

		if(!$this->loaded())
			throw $this->exception('Unable to determine the term, which remove');
		$this->delete();

		$log=$this->add('Model_Log');
		$log->createNew("term removed");
		$log->save();

	}

	function addExam($exam){

		if(!$this->loaded())
			throw $this->exception(' Please Specify The Exam');
		$exam=$this->add('Model_Exam');
		$exam->createNew($this,$exam);
		return true;

	}

	function removeExam($exam){
		if($exam instanceof Model_Exam)
			throw $this->exception('Please pass Exam Model Only');
		if(!$exam->loaded())
			throw $this->exception('Unable To determine which exam you want delete');
		if(!$exam->isExist($exam))
			throw $this->exception('This Exam Not Available');
		$exam->remove($exam);


	}

	

}
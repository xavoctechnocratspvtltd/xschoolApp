<?php

class Model_ExamInAClass extends \Model_Table{

	public $table="exam_class";

	function init(){
		parent::init();

		$this->hasOne('Session','session_id');
		$this->hasOne('Exam','exam_id');
		$this->hasOne('Class','class_id');
		// $this->add('dynamic_model/Controller_AutoCreator');
		
	}


	function createNew($exam,$class,$session=null){
		if(!$session) $session = $this->api->currentSession;

		if($this->loaded())
			throw $this->exception("You cannot use loaded model on createNewExamClassAssociation ");
			
			$this['exam_id']=$exam->id;
			$this['class_id']=$class->id;
			$this['session_id']=$session->id;

			
			$this->save();
			
	}

	function delete($forced=false){
		parent::delete();
	}

	function isExist($exam,$class,$session=null){
		if($session==null) $session=$this->api->currentSession;
		
		if($this->loaded())
			throw $this->exception("You cannot use loaded Model on isExist function in Model_ExamInAClass");
			

		$this->addCondition('class_id',$class->id);
		$this->addCondition('exam_id',$exam->id);
		$this->addCondition('session_id',$session->id);
		$this->tryLoadAny();

		if($this->loaded())
			return $this;
		else
			return false;

	}

	function associatedExamWithClass($class,$session=null){

		if($session==null) $session=$this->api->currentSession;
		$temp=$this;
		$temp->addCondition('class_id',$class->id);
		$temp->addCondition('session_id',$session->id);
		$exam_ids=array(0);
		foreach ($this as $junk) {
			$exam_ids[]=$temp['exam_id'];
		// throw new Exception($temp['exam_id']);
		// return;
		}

		$exams=$this->add('Model_Exam');
		$exams->filterByID($exam_ids);

		return $exams;

	}

	function associatedClassWithExam($exam,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('exam_id',$exam->id);
		$this->addCondition('session_id',$session->id);
		$class_ids=array(0);
		foreach ($this as $junk) {
			$class_ids[]=$this['class_id'];
		}

		$classes=$this->add('Model_Class');
		$classes->getAllClass($class_ids);

		return $classes;

	}

}
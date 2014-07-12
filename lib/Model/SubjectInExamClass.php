<?php
class Model_SubjectInExamClass extends Model_Table {
	var $table= "subjects_in_exam_class";
	function init(){
		parent::init();

		$this->hasOne('Class','class_id');
		$this->hasOne('Exam','exam_id');
		$this->hasOne('Subject','subject_id');
		$this->hasOne('Session','session_id');
		$this->addField('max_marks');
		$this->addField('min_marks');
		$this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew($subject,$exam,$class,$session=null){

		if($this->loaded())
			throw $this->exception('You can not use laoded Model');

		if(!$session)
			$session=$this->api->currentSession;
		$this['subject_id']=$subject->id;
		$this['exam_id']=$exam->id;
		$this['class_id']=$class->id;
		$this['session_id']=$session->id;
		$this->save();

		return $this;

	}

	function remove()
}
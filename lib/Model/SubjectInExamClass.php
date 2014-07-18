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
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		
		$marks=$this->add('Model_Student_Marks');
		$marks->addCondition('exam_id',$this['exam_id']);
		$marks->addCondition('subject_id',$this['subject_id']);
		$marks->addCondition('class_id',$this['class_id']);
		$marks->addCondition('session_id',$this->api->currentSession->id);
		
		if($marks->sum('marks')->debug()->getOne()>0)
			$this->api->js()->univ()->errorMessage('First remove marks assign to student')->execute();		
		
	}



	function createNew($subject,$exam,$class,$other_fields=array(),$session=null){

		if($this->loaded())
			throw $this->exception('You can not use laoded Model');

		if(!$session)
			$session=$this->api->currentSession;
		$this['subject_id']=$subject->id;
		$this['exam_id']=$exam->id;
		$this['class_id']=$class->id;
		$this['session_id']=$session->id;
		$this['max_marks']=$other_fields['max_marks'];
		$this['min_marks']=$other_fields['min_marks'];
		$this->save();

		return $this;

	}

	function isAvailable($subject,$exam,$class){

		$this->addCondition('subject_id',$subject->id);
		$this->addCondition('exam_id',$exam->id);
		$this->addCondition('class_id',$class->id);
		$this->tryLoadAny();
		if($this->loaded())
			return $this;
		else
			return false;

	}

	function remove(){
		if(!$this->loaded())
			throw $this->exception('Unable To determine record');
		$this->delete();
	}

	function maxMarks($exam,$subject,$class){
		$max_marks=$this->add('Model_SubjectInExamClass');
		$max_marks->addCondition('subject_id',$subject->id);
		$max_marks->addCondition('exam_id',$exam->id);
		$max_marks->addCondition('class_id',$class->id);
		$max_marks->addCondition('session_id',$this->api->currentSession->id);
		$max_marks->tryLoadAny();
		
		if($max_marks->loaded()){
			
			return $max_marks['max_marks'];
		}
		else
			false;
	}

}
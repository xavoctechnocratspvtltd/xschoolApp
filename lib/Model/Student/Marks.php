<?php

class Model_Student_Marks extends Model_Table {
	var $table= "marks";
	function init(){
		parent::init();

		$this->hasOne('Student','student_id');
		$this->hasOne('Class','class_id');
		$this->hasOne('Exam','exam_id');
		$this->hasOne('Subject','subject_id');
		$this->hasOne('Session','session_id');
		$this->addField('marks')->type('money')->display(array('grid'=>'grid/inline'));
		$this->addHook('beforeSave',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$subject=$this->add('Model_Subject')->load($this['subject_id']);
		$exam=$this->add('Model_Exam')->load($this['exam_id']);
		$class=$this->add('Model_Class')->load($this['class_id']);

		$max_marks=$this->add('Model_SubjectInExamClass')->maxMarks($exam,$subject,$class);

		
		if($this['marks']>$max_marks)
			$this->api->js()->univ()->errorMessage('Can Not Give Marks More Than Max Marks')->execute();
	}

	function createNew($marks,$student,$subject,$exam,$class,$session=null){
		if($this->loaded())
			throw $this->exception('createNew must Call on Empty Model');
		if(!$session)
			$session=$this->api->currentSession;
		
		$this['marks']=$marks;
		$this['student_id']=$student->id;
		$this['class_id']=$class->id;
		$this['exam_id']=$exam->id;
		$this['subject_id']=$subject->id;
		$this['session_id']=$session->id;
		$this->saveAndUnload();

		$log=$this->add('Model_Log');
		$log->createNew("Student Marks Created");
		$log->save();
	}

	function remove(){
		if(!$this->loaded())
			throw $this->exception(' Unable To determine the which record should be delete');
		$this->delete();
	}

	function isAvailable($student,$subject,$exam,$class,$session=null){
		$model=$this->add('Model_Student_Marks');
		$model->addCondition('student_id',$student->id);
		$model->addCondition('subject_id',$subject->id);
		$model->addCondition('exam_id',$exam->id);
		$model->addCondition('class_id',$class->id);

		$model->tryLoadAny();

		if($model->loaded())
			return true;
		else
			return false;
	}

	
}
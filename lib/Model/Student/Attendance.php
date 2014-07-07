<?php
class Model_Student_Attendance extends Model_Table{
 	var $table="student_attendance";
	function init(){
		parent::init();

		$this->hasOne('Class','class_id');
		$this->hasOne('Student','student_id');
		$this->hasOne('Session','session_id');

		$this->addField('month')->setValueList(array('1'=>'Jan',
            							'2'=>'Feb',
            							'3'=>'March',
            							'4'=>'April',
            							'5'=>'May',
            							'6'=>'Jun',
            							'7'=>'July',
            							'8'=>'Augest',
            							'9'=>'Sep',
            							'10'=>'Oct',
            							'11'=>'Nov',
            							'12'=>'Dec'
            							));
		$this->addField('total_attendance');
		$this->addField('present')->display(array('grid'=>'grid/inline'));

		$this->addExpression("roll_no")->set(function($m,$q){
			return $m->refSQL('student_id')->fieldQuery('roll_no');
		});

		 $this->add('dynamic_model/Controller_AutoCreator');
		$this->addHook('beforeSave',$this);
	}

	function beforeSave(){
		if($this['present'] > $this['total_attendance'])
			$this->owner->js()->univ()->errorMessage("Present can not be greater then Total Attendance")->execute();
	}

	function createNew($class,$student,$month,$total_attendance){
		if($this->loaded())
			throw $this->exception('Please use empty Model');
		$this['class_id']=$class->id;
		$this['student_id']=$student->id;
		$this['month']=$month;
		$this['total_attendance']=$total_attendance;
		$this->save();
		return $this;
	}

	function students($class,$month,$session=null,$count=fales){
		if(!$session) $session=$this->api->currentSession;
		if(!$class instanceof Model_Class)
			throw $this->exception(' student must be passed Loaded Class Object');
		 $this->addCondition('class_id',$class->id);
		 $this->addCondition('month',$month);
		 $this->addCondition('session_id',$session->id);
		 if($count)
			return $count= $this->count()->getOne();
		else
			return $this;
	}

	function isExist($class,$student,$month,$session=null){
		if(!$session) $session=$this->api->currentSession;
		if(!$class instanceof Model_Class)
			throw $this->exception(' isExist must be passed Loaded Class Object');
		if(!$student instanceof Model_Student)
			throw $this->exception(' isExixt must be passed Loaded Student Object');
		 
		 $this->addCondition('class_id',$class->id);
		 $this->addCondition('student_id',$student->id);
		 $this->addCondition('session_id',$session->id);
		 $this->addCondition('month',$month);
		 $this->tryLoadAny();

		 if($this->loaded())
			return $this;
		else
			return false;
	}

}
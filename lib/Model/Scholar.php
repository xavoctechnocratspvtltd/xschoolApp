<?php


class Model_Scholar extends \Model_Table{
public $table="scholars";
	function init(){
		parent::init();

		$this->addField('admission_date')->type('date')->mandatory("Required Field")->caption('Date Of Joining')->defaultValue(date('d-m-Y'));
		$this->addField('scholar_no')->mandatory("Scholar Number is Must")->sortable(true)  ;
		$this->addField('name')->mandatory("Name is Must")->caption('Student Name');
		$this->addField('father_name')->mandatory("Required Field")->caption('Father`s Name');
		$this->addField('mother_name')->mandatory("Required Field")->caption('Mother`s Name');                
		$this->addField('dob')->type('date')->mandatory("Required Field")->caption('Date of Birth');
		$this->addField('phone_no')->mandatory("Required Field");
		$this->addField('address')->type('text')->mandatory("Required Field");

		$this->addField('leaving_date')->type('date')->system(true);
		$this->addField('category');
		$this->addField('cast');
		$this->addField('house');

		$this->addField('blood_group');
		$this->addField('brother_sister_name_class')->type('text');
		$this->addField('form_no');
		$this->addField('gender')->enum(array('Male','FeMale'));
		$this->addField('previous_school_and_class')->type('text');



		$this->addExpression('detailed_name')->set('concat(name," :: ", father_name)');

		$this->hasMany('Student','scholar_id');

		$this->addhook('editing',array($this,'defautlEditingMode'));

		$this->addHook('beforeDelete',array($this,'defaultBeforeDelete'));

		$this->add('dynamic_model/Controller_AutoCreator');
	}



	function defautlEditingMode(){
		$this->getElement('scholar_no')->system(true);
		$dsql= $this->_dsql();
		$dsql->sql_templates['update']="update [table] set [set] [where]";
	}

	function delete($force=false){
		$st=$this->ref('Student');
		if($force){
			foreach ($st as $junk) {
				$st->delete($force);
			}

			if($st->count()->getOne()> 0)
				throw $this->exception('you can not delete, It contain student');
			$st->delete();
		}
	}


	function defaultBeforeDelete(){
		if($this->ref('Student')->count()->getOne() > 0)
			throw $this->exception('Scholar is student in any of session, Cannot delete');
	}


	function createNew($name,$father_name,$other_fields=array(),$form=null){
		$this['name']=$name;
		$this['father_name']=$father_name;

		foreach ($other_fields as $key => $value) {
			$this[$key] = $value;
		}

		$this->save();
	}


	function isInClass($class,$session=null){
		if(!$session) $session = $this->api->currentSession;

		$student = $this->add('Model_Student');

		if($student->isInClass($class,$session,$this->id))
			return $student;
		else
			return false;

	}
	
	function assignClass($class,$as_student_type){
		if(! $class instanceof Model_Class)
			throw $this->exception('$class must be Class Instance');

		if($this->isStudent())
			throw $this->exception('Student is already assigned a class, remove first from class');

		$class->addStudent($this,$as_student_type);
	}



	function removeFromClass($class){
		if(!$this->loaded())
			throw $this->exception('Scholar must be loaded to rmove from a class');

		if(! $class instanceof Model_Class)
			throw $this->exception('$class must be Class Instance');

		if(!$this->isStudent())
			throw $this->exception('Scholar is not assigned in any class');

		if(!$class->hasStudent($this))
			throw $this->exception('Scholar is not student of provided class');

		$this->student()->delete();


	}


	function promoteToClass($class=null){


	}
	function demoteToClass($class=null){


	}
	// function editScholar(){} ???
	function deleteScholar(){


	}
	
	function getNewScholarNumber($for_branch=false){
		$scholars=$this->add('Model_Scholar');
		if($for_branch)
			$scholars->addCondition('branch_id',$for_branch->id);

		$max_no = $scholars->_dsql()->del('fields')->field('MAX(scholar_no)')->getOne();

		return $max_no+1;
	}

	function isStudent($session=null){
		if($session==null) $session=$this->api->currentSession;

		$student = $this->ref('Student')->addCondition('session_id',$session->id);
		$student->tryLoadAny();

		if($student->loaded())
			return $student;
		else
			return false;
	}


	function student($session=null){
		if($session==null) $session=$this->api->currentSession;
		return $this->isStudent($session);
	}
}
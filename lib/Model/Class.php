<?php


class Model_Class extends \Model_Table{
public $table="classes";
	function init(){
		parent::init();

        $this->hasOne('Branch','branch_id')->mandatory(true);
        // $this->hasOne('ClassPrev','previous_class_id','full_name');
		$this->addField('name')->mandatory("Please give a class name")->caption('Class Name');
        $this->addField('section')->mandatory('Give section a name');

        $this->hasMany('CurrentStudent','class_id');
        $this->hasMany('ExamInAClass','class_id');
        $this->hasMany('SubjectInAClass','class_id');
        $this->hasMany('FeesInAClass','class_id');
        // $this->hasMany('ClassPrev','previous_class_id');

        $this->addExpression('full_name')->set('(concat(name," - ",section))');      

        $this->addHook('editing',array($this,'editingDefault'));
        $this->addHook('beforeDelete',$this);
        $this->addHook('beforeSave',$this);
        
        
        // $this->add('dynamic_model/Controller_AutoCreator');

	}

	function beforeSave(){

		// if(
		// 	$this['previous_class_id'] and 
		// 	$this->add('Model_Class')
		// 		->addCondition('id','<>',$this->id)
		// 		->addCondition('previous_class_id',$this['previous_class_id'])
		// 		->tryLoadAny()->loaded()
		// ) throw $this->exception('The Class is already used for another class as prev class', 'ValidityCheck')->setField('previous_class_id');

		// if($this['previous_class_id'] AND $this['previous_class_id'] == $this['id'])
		// 	throw $this->exception('Prev class cannot be same', 'ValidityCheck')->setField('previous_class_id');
	}

	function editingDefault(){
		$this->getElement('name')->system(true);
	}


	function createNew($name,$section,$branch,$other_fields=array(),$form=null){

		$this['name']=$name;
		$this['branch_id']=$branch->id;
		$this['section']=$section;

		unset($other_fields['name']);
		unset($other_fields['branch_id']);
		unset($other_fields['section']);

		foreach ($other_fields as $key => $value) {
			$this[$key] = $value;
		}

		$this->save();

	}

	function deleteForced(){
		$cs=$this->ref('CurrentStudent');
		$fic=$this->ref('FeesInAClass');
		$sic=$this->ref('SubjectInAClas');
		$ca=$this->ref('Attendance');

		foreach ($cs as $junk) {	
			$cs->delete($force); 
		}

		foreach ($fic as $junk) {	
			$fic->delete($force); 
		}

		foreach ($sic as $junk) {
			$sic->delete($force);
		}

		foreach ($ca as $junk) {	
			$ca->delete($force); 
		}

		$this->delete();
	}

	function beforeDelete(){

			if($this->ref('CurrentStudent')->count()->getOne() > 0)
				throw $this->exception('You can not delete, It contain student record');
				

			if($this->ref('ExamInAClass')->count()->getOne() > 0)
				throw $this->exception('You can not delete, It contain assocaited exams record');
				
			if($this->ref('SubjectInAClass')->count()->getOne() > 0)
				throw $this->exception('You can not delete, It contain assocaited subject record');
			
			if($this->ref('FeesInAClass')->count()->getOne() > 0)
				throw $this->exception('You can not delete, It contain associated fees record');
			
	}


	function filterByIDs($class_id_array){
		$this->addCondition('id',$class_id_array);
		return $this;
	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}

	//// Subjects MANAGE Start/////

	function addSubject($subject,$session=null){

		

		if(!$subject instanceof Model_Subject)
			throw $this->exception(' addSubject must be passed Loaded Subject Object');

		if($session==null) $session=$this->api->currentSession;

		if($this->hasSubject($subject,$session))

			throw $this->exception('Already in the class');
		
		


		$newsub = $this->add('Model_SubjectInAClass');
		$newsub->createNew($this,$subject,$session);
	}

	function removeSubject($subject,$force=false){

		if(!$subject instanceof Model_Subject)
			throw $this->exception(' removeSubject must be passed Loaded Subject Object');

		if(!($sub_in_class=$this->hasSubject($subject))) // returning SubjectInClass --- Not subject Model
			throw $this->exception('This Subject is not Available in Class');

		$sub_in_class->delete($force);
	}

	function hasSubject($subject,$session=null){

		if(!$session) $session = $this->api->currentSession;

		$sub = $this->add('Model_SubjectInAClass');
		
		if($sub->isExist($this,$subject,$session))
			return $sub;
		else
			return false;


	}

	function allSubjects($session=null){
		if(!$this->loaded()) throw $this->exception('Class Must be loaded to get subjects');
		if(!$session) $session = $this->api->currentSession;

		$sub_in_class = $this->add('Model_SubjectInAClass');
		return $sub_in_class->associatedSubjectWith($this,$session);
		
	}

	//// Subjects MANAGE END/////

	//// Fees MANAGE Start/////

	function addFees($fees,$applyOnAllClassStudents=true){

		if(!$fees instanceof Model_Fees)
			throw $this->exception(' addFees must be passed Loaded Fees Object');

		if($this->hasFees($fees))
			throw $this->exception(' Allready Apply In Class');
		
		$feesInClass= $this->add('Model_FeesInAClass');
		$feesInClass->createNew($this,$fees);

		if($applyOnAllClassStudents){
			foreach ($student=$this->allStudents() as $junk) {
					// $student->createStudentFeesAssociation($fees);
					$student->addFees($fees);
			}
		}
	}

	function removeFees($fees,$removeFromAllClassStudents=true){

		if(! ($fees instanceof Model_Fees) )
			throw $this->exception(' removeFees must be passed Loaded Fees Object');

		if(!($f=$this->hasFees($fees))) // fees_in_a_class object returned
			throw $this->exception('Fees Not Apply in the class');

		// TODO ASK FOR DELETE FUNCTION
			
		if($removeFromAllClassStudents){
			
			foreach ($student=$this->allStudents() as $junk) {
						$student->removeFees($fees);
			}
		}

		$f->delete();

	}

	function hasFees($fees,$session=null){
		if(!$session) $session=$this->api->currentSession;

		$fees_in_class = $this->add('Model_FeesInAClass');
		if($fees_in_class->isExist($this,$fees,$session))
			return $fees_in_class;
		else
			return false;

	}

	function feeses($session=null){
		return $this->allFees($session);
	}

	function allFees($session=null){
		if(!$this->loaded()) throw $this->exception('Class Must be loaded to get subjects');
		if(!$session) $session = $this->api->currentSession;

		$fees_in_class = $this->add('Model_FeesInAClass');
		return $fees_in_class->associatedFeesWithClass($this,$session);
		
	}

	// Fees Manage END///

	// Exam Manage Start///

	function addExam($exam,$session=null){
		if($session==null) $session=$this->api->currentSession;
		
		if(!$exam instanceof Model_Exam) 
			throw $this->exception(' addExam Must be passed Loaded Exam Object');

		if($this->hasExam($exam,$session))
			throw $this->exception('Already in the class');
		// throw new Exception($session->id);

		$examnew = $this->add('Model_ExamInAClass');
		$examnew->createNew($exam,$this,$session);

	}

	function removeExam($exam,$force=null){

		if(!$exam instanceof Model_Exam)
			throw $this->exception(' removeExam must be passed Loaded Exam Object');

		if(!($exam_in_class=$this->hasExam($exam)))
			throw $this->exception('This Exam is not Available in Class');		

		$exam_in_class->delete($force);

	}

	function hasExam($exam,$session=null){
		if(!$session) $session=$this->api->currentSession;


		$exam_in_class = $this->add('Model_ExamInAClass');

		if($exam_in_class->isExist($exam,$this,$session))
			return $exam_in_class;
		else
			return false;


	}

	function allExams($session=null){

		if(!$this->loaded()) throw $this->exception('Class Must be loaded to get exams');
		if(!$session) $session = $this->api->currentSession;

		$exam = $this->add('Model_ExamInAClass');
		
		return $exam->associatedExamWithClass($this,$session);
		// throw new Exception("Class".$this->id."sess".$session->id);
		
		


	}

	// Exam Manage END///

	// Student Manage START///

	function addScholar($scholar, $student_type, $apply_class_fee=true){
		$this->addStudent($scholar, $student_type,$apply_class_fee);	
	}

	function addStudent($scholar, $student_type, $apply_class_fee=true){
		if(!$scholar instanceof Model_Scholar)
			throw $this->exception(' addStudent must be passed Loaded Scholar Object');

		if($this->hasStudent($scholar))
			throw $this->exception('Already in the class');

		$student = $this->add('Model_Student');
		$student->createNew($scholar,$this,$student_type);

		if($apply_class_fee){
			foreach ($f=$this->feeses() as $junk) {
				$student->addFees($f);
			}
		}

	}

	function removeStudent($scholar, $force=false){

		if(!$scholar instanceof Model_Scholar)
			throw $this->exception(' addStudent must be passed Loaded Scholar Object');

		if(!($s=$this->hasStudent($scholar)))
			throw $this->exception('This Student is not Available in Class');		

		$s->delete($force);
	}

	function hasStudent($scholar,$session=null){
		if(!$session) $session=$this->api->currentSession;

		$student = $this->add('Model_Student');
		if($student->isExist($this,$scholar,$session))
			return $student;
		else
			return false;

	}

	function students($session=null){
		return $this->allStudents($session);
	}

	function allStudents($session=null){
		if(!$this->loaded()) throw $this->exception('Class Must be loaded to get students');
		if(!$session) $session = $this->api->currentSession;

		$student = $this->add('Model_Student');
		$student->addCondition('class_id',$this->id);
		$student->addCondition('session_id',$session->id);
		return $student;
	}
	
	// ========== Student Manage END///
	
	function getNewScholarNumber(){
		
	}

}
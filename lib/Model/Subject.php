<?php


class Model_Subject extends \Model_Table{
public $table="subjects";
	function init(){
		parent::init();

	 $this->addField('name')->mandatory(true);
	 $this->addField('order')->type('int');
	 $this->hasMany('SubjectInAClass','subject_id');

	 $this->addHook('beforeDelete',$this);

	 $this->add('dynamic_model/Controller_AutoCreator');


	}

	function beforeDelete(){
		if($this->ref('SubjectInAClass')->count()->getOne() > 0 ){
			throw new Exception("This subject is associated with some class", 1);
			
		}
	}

	function createNew($name,$other_fields=array(),$form=null){
		$this['name']=$name;
		$this->save();

		$log=$this->add('Model_Log');
		$log->createNew("subject created");
		$log->save();
		return $this;
	}

	function filterByIDs($subject_ids_array){

		$this->addCondition('id',$subject_ids_array);
		return $this;
	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}

	function associateWithClass($class,$session=null){

		if($class instanceof xschoolApp\Model_Class)
			throw $this->exception("associateWithClass Must Be Pass loaded object of Class");
		if($class->isAssociateWithClass($class))
			throw $this->exception("This is Class Is allready associated with class");
		if($session==null) $session=$this->api->currentSession; 

		$associate_subject_with_class=$this->add('Model_SubjectClassMap');
		$associate_subject_with_class->createNewSubjectClassAssociation($class,$subject,$session);
			
	}

	function isExist($class,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$subject=$this->add('Model_SubjectClassMap');
		$subject->addCondition('subject_id',$this->id);				
		$subject->addCondition('class_id',$class->id);				
		$subject->addCondition('session_id',$session->id);
		$subject->tryLoadAny();

		if($subject->loaded())
			return true;
		else
			return false;				

	}


	function removeFromClass($class,$force=false){

		if($class instanceof xschoolApp\Model_Class)
			throw $this->exception("removeFromClass Must Be Pass loaded object of Class");
		
		if(!($class=$this->isAssociateWithClass($class)))
			throw $this->exception("Given class is not associated with Subject");

		$class->delete($force);



	}
	function feeType(){}
}
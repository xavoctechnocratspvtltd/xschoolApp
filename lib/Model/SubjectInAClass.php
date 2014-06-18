<?php


class Model_SubjectInAClass extends \Model_Table{
	public $table="subjects_in_classes";
	function init(){
		parent::init();

		$this->hasOne('Class','class_id');
		$this->hasOne('Session','session_id');
		$this->hasOne('Subject','subject_id');

		$this->add('dynamic_model/Controller_AutoCreator');

	}


	function createNew($class,$subject,$session){

		if($this->loaded())
			throw $this->exception("You can not use loaded model on createNewSubjectClassAssociation");
			// throw new Exception("class".$class->id."sub ".$subject->id." see".$class->id, 1);

			$this['subject_id']=$subject->id;
			$this['class_id']=$class->id;
			$this['session_id']=$session->id;
			$this->save();
	}

	function delete(){
		parent::delete();
	}

	function isExist($class,$subject,$session){
		if($this->loaded())
			throw $this->exception("You cannot use loaded Model on isExist function in Model_FeesInAClass");
								
		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('class_id',$class->id);
		$this->addCondition('subject_id',$subject->id);
		$this->addCondition('session_id',$session->id);
		$this->tryLoadAny();

		if($this->loaded())
			return $this;
		else
			return false;

	}

	function associatedSubjectWith($class,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$temp = $this;

		$temp->addCondition('class_id',$class->id);
		$temp->addCondition('session_id',$session->id);
		$subject_ids=array(0);
		foreach ($temp as $junk) {
			$subject_ids[]=$temp['subject_id'];
		}

		$subjects=$this->add('Model_Subject');
		$subjects->filterByIDs($subject_ids);

		return $subjects;

	}

	function associatedClassesWith($subject,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('subject_id',$subject->id);
		$this->addCondition('session_id',$session->id);
		$class_ids=array();
		foreach ($this as $junk) {
			$class_ids[]=$this['class_id'];
		}

		$classes=$this->add('Model_Class');
		$classes->filterByIDs($class_ids);

		return $classes;

	}
}
<?php


class Model_FeesInAClass extends \Model_Table{
	public $table="feeses_in_classes";

	function init(){
		parent::init();

		$this->hasOne('Session','session_id');
		$this->hasOne('Fees','fees_id');
		$this->hasOne('Class','class_id');
		// $this->add('dynamic_model/Controller_AutoCreator');

		
	}

	
	function createNew($class,$fees,$session=null){

		if($this->loaded())
				throw $this->exception('You cannot use loaded Model on createNewClassFessAssociation');
		if(!$session) $session = $this->api->currentSession;

		$this['class_id']=$class->id;
		$this['fees_id']=$fees->id;
		$this['session_id']=$session->id;
		$this->save();
		
			

	}

	function delete(){
		parent::delete();
	}

	function isExist($class,$fees,$session){
		if($this->loaded())
			throw $this->exception("You cannot use loaded Model on isExist function in Model_FeesInAClass");
			
		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('class_id',$class->id);
		$this->addCondition('fees_id',$fees->id);
		$this->addCondition('session_id',$session->id);
		$this->tryLoadAny();

		if($this->loaded())
			return $this;
		else
			return false;

	}

	function associatedFeesWithClass($class,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('class_id',$class->id);
		$this->addCondition('session_id',$session->id);
		$fees_ids=array(0);
		foreach ($this as $junk) {
			$fees_ids[]=$this['fees_id'];
		}

		$fees=$this->add('Model_Fees');
		$fees->filterByID($fees_ids);

		return $fees;

	}

	function associatedClassWithFees($fees,$session=null){

		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('fees_id',$fees->id);
		$this->addCondition('session_id',$session->id);
		$class_ids=array();
		foreach ($this as $junk) {
			$fees_ids[]=$this['class_id'];
		}

		$classes=$this->add('Model_Class');
		$classes->getAllClass($class_ids);

		return $classes;

	}


}
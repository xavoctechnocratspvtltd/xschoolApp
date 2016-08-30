<?php

/**
* 
*/
class Controller_ACL extends AbstractController{
	public $page_name=null;
	function init(){
		parent::init();

		if(!$this->page_name) $this->page_name=str_replace('page_','',  get_class($this->owner));

		$m=$this->add('Model_ACL');
		$m->addCondition('page',$this->page_name);
		$m->addCondition('staff_id',$this->api->auth->model->id);
		$m->tryLoadAny();

		if(!$m->loaded()){
			$m->save();
		}
		if(!$m['is_allow'] And !$this->api->auth->model['is_application_user']){
			$this->owner->add('View_Error')->set('Not Allow');
			throw $this->exception('','StopInit');
		}

	}
}
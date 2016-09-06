<?php

class page_staffAcl extends Page{
	function init(){
		parent::init();
		$staff_id=$this->api->stickyGET('staff_id');
		$m=$this->add('Model_ACL');
		$m->addCondition('staff_id',$staff_id);
		$m->tryLoadAny();
		if($m->loaded()){
			$m->getElement('page')->editable(false);
			$c=$this->add('CRUD',array('allow_add'=>false,'allow_del'=>false));
			$c->setModel($m);
		}else{
			// $this->add('View_Info')->set('This Staff Not visit on this page');
		}
	}
}
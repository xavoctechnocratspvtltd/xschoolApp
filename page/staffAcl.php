<?php

class page_staffAcl extends Page{
	function init(){
		parent::init();
		$staff_id=$this->api->stickyGET('staff_id');
		$m=$this->add('Model_ACL');
		$m->tryLoadBy('staff_id',$staff_id);
		$m->getElement('page')->editable(false);
		$c=$this->add('CRUD',['allow_add'=>false,'allow_del'=>false]);
		$c->setModel($m);
	}
}
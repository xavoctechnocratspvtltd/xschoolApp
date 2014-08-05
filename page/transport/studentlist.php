<?php

class page_transport_studentlist extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$vehicle_field=$form->addField('dropdown','vehicle');
		$vehicle_field->setModel($this->api->currentBranch->vehicle());
		$form->addSubmit('GET LIST');

		

	}
}
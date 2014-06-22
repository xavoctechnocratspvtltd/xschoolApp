<?php

class page_setdate extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->addField('DatePicker','date');
		$form->addSubmit('Set Date');

		if($form->isSubmitted()){
			$this->api->setDate($form['date']);
			$form->js(null,$form->js()->_selector('.welcome-block')->trigger('reload'))->reload()->execute();
		}

	}
}
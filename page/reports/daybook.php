<?php

class page_reports_daybook extends Page {
	function init(){
		parent::init();

		$form= $this->add('Form');
		$form->addField('DatePicker','date');
		$form->addSubmit('DayBook');

	}
}
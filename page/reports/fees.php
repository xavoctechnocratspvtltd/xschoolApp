<?php

class page_reports_fees extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');
		$form->addSubmit('Get List');

		$v=$this->add('View_FeesReport',array('from_date'=>$_GET['from_date'],'to_date'=>$_GET['to_date']));

		if($form->isSubmitted()){
			$v->js()->reload(array(
					'from_date'=>$form['from_date']?:0,
					'to_date'=>$form['to_date']?:0,
				))->execute();
		}
	}
}
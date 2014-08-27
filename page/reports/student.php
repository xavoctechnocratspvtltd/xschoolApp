<?php

class page_reports_student extends Page {
	function init(){
		parent::init();

		$this->api->stickyGET('class_id');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class=$this->api->currentBranch->classes();
		$class->title_field='full_name';
		$class_field->setModel($class);

		$form->addSubmit('Get List');

		$scholar=$this->add('Model_Scholar');
		$st=$scholar->leftJoin('students.scholar_id','id');
		$st->hasOne('Class','class_id');
		$st->hasOne('Vehicle','vehicle_id');

		$scholar->getElement('form_no')->system(true);
		$scholar->getElement('previous_school_and_class')->system(true);
		$scholar->getElement('detailed_name')->system(true);
		$grid=$this->add('Grid');
		if($_GET['class_id'])
			$scholar->addCondition('class_id',$_GET['class_id']);
		else
			$scholar->addCondition('id',-1);
		$grid->setModel($scholar);

		$grid->add('misc/Export');

		if($form->isSubmitted()){
			$grid->js()->reload(array('class_id'=>$form['class']))->execute();
		}
	}
}
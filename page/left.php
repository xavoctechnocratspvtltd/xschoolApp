<?php

class page_left extends Page{
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class=$this->add('Model_Class');
		$class->title_field='full_name';
		$class_field->setModel($class);
		$form->addSubmit('GET LIST');


		$grid=$this->add('Grid');
		$student=$this->add('Model_CurrentStudent');
		$student->addCondition('is_left',false);

		if($_GET['mark_left']){
			// $this->api->stickyGET('mark_left');
			$all_available_student=$this->add('Model_CurrentStudent');
			$all_available_student->load($_GET['mark_left']);
			$all_available_student->markLeft();
			$grid->js()->reload()->execute();
		}
		if($_GET['class']){
			$this->api->stickyGET('class');
			$student->addCondition('class_id',$_GET['class']);
		}
		// else
		// 	$student->addCondition('id',-1);

		$grid->setModel($student);
		$grid->addColumn('button','mark_left');

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();
		}
	}
}
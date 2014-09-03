<?php

class page_master_student_attendancereport extends Page{
	function init(){
		parent::init();


		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);

		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$attendance=$this->add('Model_CurrentStudent');

		$attendance->addExpression('total_meetings')->set(function($m,$q){
			return $m->refSQL('Student_Attendance')->sum('total_attendance');
		});

		$attendance->addExpression('total_present')->set(function($m,$q){
			return $m->refSQL('Student_Attendance')->sum('present');
		});

		if($_GET['class']){
			$this->api->stickyGET('class');
			$attendance->addCondition('class_id',$_GET['class']);
		}
		else
			$attendance->addCondition('id',-1);
		$grid->setModel($attendance,array('name','total_meetings','total_present'));
		$grid->addPaginator(50);

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();
		}
	}
}
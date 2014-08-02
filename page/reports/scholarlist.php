<?php

class page_reports_scholarlist extends Page{
	function init(){
		parent::init();

		$class=$this->add('Model_Class');
		$class->title_field='full_name';
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class);

		$form->addSubmit('GET LIST');

		$scholar_model=$this->add('Model_Scholar');
		

		$scholar_model->addExpression('class')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'cc'));
			$class_join = $student_m->join('classes','class_id');
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->addCondition('session_id',$m->api->currentSession->id);

			return $student_m->_dsql()->del('fields')->field($student_m->dsql()->expr('name'));
		});

		$grid=$this->add('Grid');
		if($_GET['class']){
			$class->load($_GET['class']);
			$scholar_model->addCondition('class',$class['name']);
		}
		$grid->setModel($scholar_model);

		$grid->addPaginator(50);
		$grid->add('misc/Export');

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();

		}

	}
}
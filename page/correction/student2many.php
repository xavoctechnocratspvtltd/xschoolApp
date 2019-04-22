<?php

class page_correction_student2many extends Page {
	function initMainPage(){
		// parent::init();

		$this->add('View')->setElement('h2')->set('Scholars Assign in Class More then 1 in current session');

		$scholar_model = $this->add('Model_Scholar');
		$scholar_model->addExpression('student_count')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'cc'));
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->addCondition('session_id',$m->api->currentSession->id);
			return $student_m->count();
		});
		$scholar_model->addCondition("student_count",'>',1);
		$grid = $this->add('Grid');
		$grid->setModel($scholar_model);
		$grid->addPaginator(50);
		$grid->addColumn('expander','assign_class');

	}

	function page_assign_class(){
		$sid = $this->api->stickyGET('scholars_id');

		$student_m = $this->add('Model_Student');
		$student_m->addCondition('scholar_id',$sid);
		$student_m->addCondition('session_id',$this->api->currentSession->id);
		$crud = $this->add('CRUD',['allow_add'=>false,'allow_edit'=>false]);
		$crud->setModel($student_m);

	}
}
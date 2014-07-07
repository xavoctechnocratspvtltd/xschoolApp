<?php

class page_scholar_addtoclass extends Page {
	function initMainPage(){
		// parent::init();

		$grid=$this->add('Grid')->addClass('free-scholar-grid');
		$grid->js('reload')->reload();

		$scholar_model = $this->add('Model_Scholar');


		$scholar_model->addExpression('current_class')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'cc'));
			$class_join = $student_m->join('classes','class_id');
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->addCondition('session_id',$m->api->currentSession->id);

			return $student_m->_dsql()->del('fields')->field($student_m->dsql()->expr('concat(name," ",section)'));
		});

		$scholar_model->addCondition('current_class',null);
		$grid->setModel($scholar_model);

		$grid->addColumn('expander','assign_class');
		$grid->addPaginator(50);
		$grid->addQuickSearch(array('name','father_name','phone_no','scholar_no'));
	}

	function page_assign_class(){

		$this->api->stickyGET('scholars_id');
		$scholar = $this->add('Model_Scholar')->load($_GET['scholars_id']);
		$class_model=$this->add('Model_Class');
		$class_model->title_field='full_name';

		$form=$this->add('Form');
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select')->validateNotNull();
		$class_field->setModel($class_model);
		
		$student_field=$form->addField('dropdown','student_type')->setEmptyText('Please Select')->validateNotNull();
		$student_field->setModel('StudentType');

		$form->addSubmit('Assign');

		if($form->isSubmitted()){
			$class_model->load($form['class']);
			$scholar->assignClass($class_model,$form['student_type']);

			$form->js(null,$form->js()->univ()->closeExpander())->_selector('.free-scholar-grid')->trigger('reload')->execute();
		}
	}
}
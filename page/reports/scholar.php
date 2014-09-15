<?php
class page_reports_scholar extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid')->addClass('free-scholar-grid');
		$grid->js('reload')->reload();

		$scholar_model = $this->add('Model_Scholar');


		$scholar_model->addExpression('previous_class')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'pc'));
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->setOrder('id','desc');
			$student_m->setLimit(1);

			return $student_m->fieldQuery('class');
		});

		$scholar_model->addExpression('current_class')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'cc'));
			$class_join = $student_m->join('classes','class_id');
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->addCondition('session_id',$m->api->currentSession->id);

			return $student_m->_dsql()->del('fields')->field($student_m->dsql()->expr('concat(name," ",section)'));
		});
		// throw new Exception($scholar_model['current_class'], 1);


		$scholar_model->addCondition('current_class',null);
		$grid->setModel($scholar_model,array('scholar_no','name','previous_class'));
	}
}
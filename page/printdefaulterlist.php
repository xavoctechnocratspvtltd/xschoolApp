<?php
class page_printdefaulterlist extends Page {
	function init(){
		parent::init();

		$this->js(true)->_selector('#header')->toggle();
		$this->js(true)->_selector('#footer')->toggle();
		$student_model = $this->add('Model_Student');
		
		if($_GET['class']){
			$student_model->addCondition('class_id',$_GET['class']);
		
		$student_model->_dsql()->having('applied_fees_sum_till_date <> paid_fees_sum_till_date or paid_fees_sum_till_date is null');


		$grid=$this->add('Grid');

		$grid->addMethod('format_due',function($g,$f){
			$g->current_row[$f]=$g->current_row['applied_fees_sum_till_date']-$g->current_row['paid_fees_sum_till_date'];
		});

		$grid->setModel($student_model);
		$grid->addColumn('due','due_fees');
		$grid->removeColumn('class');
		$grid->removeColumn('session');
		$grid->removeColumn('scholar');
		$grid->removeColumn('studenttype');
		$grid->removeColumn('vehicle');
		$grid->removeColumn('roll_no');

	}
	}
}
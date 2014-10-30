<?php
class page_printdefaulterlist extends Page {
	function init(){
		parent::init(); 

		$this->js(true)->_selector('#header')->toggle();
		$this->js(true)->_selector('#footer')->toggle();
		$student_model = $this->add('Model_Student');
		$student_model->addCondition('is_left',false);
		
		if($_GET['class']){
			$student_model->addCondition('class_id',$_GET['class']);
			$class=$this->add('Model_Class');
			$class->load($_GET['class']);
			$this->add('H3')->setHtml('Defaulter List of Class -'.$class['full_name'].'<small>'.$this->api->today.'</small>')->setAttr('align','center');
		}
		$student_model->_dsql()->having('applied_fees_sum_till_date <> paid_fees_sum_till_date or paid_fees_sum_till_date is null');
		$student_model->addExpression('due')->set(function($m,$q){
			$sfa=$m->add('Model_StudentAppliedFees',array('table_alias'=>'xsaf1'));
			$sfa->addCondition('student_id',$q->getField('id'));
			$sfa->addCondition('due_on','<=',$m->api->today);
			$sfa_q=$sfa->_dsql()->del('fields')->field($q->dsql()->expr('IF(sum(amount) is null, 0, sum(amount))'))->render();

			
			$ft=$m->add('Model_FeesTransaction',array('table_alias'=>'xsft'));
			$ft->addCondition('student_id',$q->getField('id'));
			$ft->addCondition('submitted_on','<=',$m->api->today);
			$ft_q = $ft->_dsql()->del('fields')->field($q->dsql()->expr('IF(sum(amount) is null, 0, sum(amount))'))->render();
			return "((".$sfa_q.") - (".$ft_q."))";
		})->type('money');

		$grid=$this->add('Grid');


		$grid->setModel($student_model);
		// $grid->addMethod('format_due',function($g,$f){
		// 	$g->current_row[$f]=$g->current_row['applied_fees_sum_till_date']-$g->current_row['paid_fees_sum_till_date'];
		// });
		// $grid->addColumn('due,money','due_fees');
		$grid->removeColumn('class');
		$grid->removeColumn('session');
		$grid->removeColumn('scholar');
		$grid->removeColumn('studenttype');
		$grid->removeColumn('vehicle');
		$grid->removeColumn('roll_no');

		$grid->addTotals(array('due'));

	}
}

<?php

class page_reports_duefees extends Page {
	function init(){
		parent::init();

		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field = 'full_name';
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('All');
		$class_field->setModel($class_model);

		$form->addSubmit('Get List');

		$student_model = $this->add('Model_Student');
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
		
		$selected_class=$this->add('Model_Class');
		if($_GET['filter']){
			if($_GET['class']){
				$selected_class->load($_GET['class']);
				$student_model->addCondition('class_id',$_GET['class']);
			}
			if(!$_GET['class']){
				
			}

		}else{
			$student_model->addCondition('class_id',-1);
		}

		$student_model->_dsql()->having('due > 0 ');
		// $student_model->_dsql()->having('applied_fees_sum_till_date <> paid_fees_sum_till_date or paid_fees_sum_till_date is null');


		$grid=$this->add('Grid')->addClass('mygrid');
		$grid->add('H1',null,'top_1')->setHTML('Class ' . $selected_class['full_name'] . '<small>'.$this->api->today.'</small>');

		// $grid->addMethod('format_due',function($g,$f){
		// 	$g->current_row[$f]=$g->current_row['applied_fees_sum_till_date']-$g->current_row['paid_fees_sum_till_date'];
		// });

		$grid->setModel($student_model);
		$grid->removeColumn('class');
		$grid->removeColumn('session');
		$grid->removeColumn('scholar');
		// $grid->removeColumn('studenttype');
		$grid->removeColumn('vehicle');
		$grid->removeColumn('roll_no');

		$grid->addTotals(array('due'));
		
		// $grid->js('click',$this->js()->univ()->newWindow($this->api->url('printdefaulterlist',array('class'=>$_GET['class']))));

		if($form->isSubmitted()){
			$grid->js()->reload(array('filter'=>1,'class'=>$form['class']))->execute();
		}

		$js=array(
			$this->js()->_selector('#header')->toggle(),
			$this->js()->_selector('#footer')->toggle(),
			$this->js()->_selector('ul.ui-tabs-nav')->toggle(),
			$this->js()->_selector('.atk-form')->toggle(),
			);

		$grid->js('click',$js);

	}
}
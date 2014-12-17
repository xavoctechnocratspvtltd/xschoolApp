<?php

class page_sms_defaulter extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(8);
		// $col2=$cols->addColumn(6);
		$form=$col1->add('Form',null,null,array('form_horizontal'));
		$branch_field=$form->addField('dropdown','branch')->setEmptyText('Please Select')->validateNotNull();
		$branch_field->setModel('Branch');
		// $form->addField('text','message')->validateNotNull();
		$form->addSubmit('Send');
		$grid=$col1->add('Grid');
		$student_model=$this->add('Model_Student');
		$class_join=$student_join_class=$student_model->join('classes','class_id');
		$branch_join=$class_join->join('branches','branch_id');
		$branch_join->addField('branch_name','name');
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
			
		 // $student_model->addCondition('class_id',$class_model->id);

		$student_model->_dsql()->having('due > 0 ');

		if($_GET['branch']){
			$this->api->stickyGET('branch');
			$branch=$this->add('Model_Branch')->load($_GET['branch']);
			$student_model->addCondition('branch_name',$branch['name']);
		}else
			$student_model->addCondition('id',-1);

		$grid->setModel($student_model,array('name','class','phone_no','due'));
		$grid->controller->importField('branch_name');
		$grid->addPaginator(10);

		$grid->addMethod('format_empty',function($g,$f){
			$sms=$g->add('Model_Sms');
			$sms->addCondition('created_at',$g->api->today);
			$no_array=$sms->senitizeNumber($g->model['phone_no']);
			$sms->addCondition('numbers','Like','%'.$no_array[0].'%');
			$sms->tryLoadAny();

			if($sms->loaded())
				$g->current_row_html[$f]='Message Sent';
		});
		$grid->addColumn('button,empty','send');

		if($_GET['send']){	
			$student=$this->add('Model_Student')->load($_GET['send']);
			$numbers=explode(',', $student['phone_no']);
			$message="Dear Parents <".$student['name'].">,
					 Your ward fee us due as last date of submission is 10th of
					 every month. Plz ignore the msg if fee paid. Regards Principal";

			$sms=$this->add('Model_Sms');
			$sms->sendMessage($message,$numbers,null);
			$grid->setFormatter('send','empty');
			$grid->js()->reload()->execute();
		}

		if($form->isSubmitted()){
			$numbers=array();
			$grid->js()->reload(array('branch'=>$form['branch']))->execute();
		}
		
	}
}
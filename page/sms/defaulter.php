<?php

class page_sms_defaulter extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(6);
		$form=$col1->add('Form');
		$branch_field=$form->addField('dropdown','branch')->setEmptyText('Please Select')->validateNotNull();
		$branch_field->setModel('Branch');
		$form->addField('text','message')->validateNotNull();
		$form->addSubmit('Send');

		if($form->isSubmitted()){
			$numbers=array();
			$branch=$this->add('Model_Branch');
			$branch->load($form['branch']);
			$class_model=$branch->classes();
			foreach ($class_model as $junk) {
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
			
		
			$student_model->addCondition('class_id',$class_model->id);

			$student_model->_dsql()->having('due > 0 ');

			foreach ($student_model as $junk) {
				$numbers[]=$student_model['phone_no'];
			}

			}

			$sms=$this->add('Model_Sms');
			$sms->sendMessage($form['message'],$numbers,null);
			$form->js()->reload(null,$form->js()->univ()->successMessage('Message Send Successfully'))->execute();
		}
		
	}
}
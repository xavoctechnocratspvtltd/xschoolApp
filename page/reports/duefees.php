<?php

class page_reports_duefees extends Page {
	function init(){
		parent::init();

		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field = 'full_name';
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class_model);

		$form->addSubmit('Get List');

		$student_model = $this->add('Model_Student');
		
		if($_GET['class']){
			$student_model->addCondition('class_id',$_GET['class']);
		}else{
			$student_model->addCondition('class_id',-1);
		}

		$student_model->_dsql()->having('applied_fees_sum_till_date <> paid_fees_sum_till_date or paid_fees_sum_till_date is null');


		$grid=$this->add('Grid');

		$grid->setModel($student_model);

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();
		}

	}
}
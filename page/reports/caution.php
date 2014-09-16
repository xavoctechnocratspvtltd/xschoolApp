<?php

class page_reports_caution extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$branch_field=$form->addField('dropdown','branch')->setEmptyText("All");
		$branch_field->setModel('Branch');
		$form->addSubmit('Get List');

		$grid=$this->add('Grid');
		$result=$this->add('Model_StudentAppliedFees');
		$result_j=$result->join('fees','fees_id');
		$result_s_j=$result->join('students','student_id');
		$result_c_j=$result_s_j->join('classes','class_id');
		$result_c_j->hasOne('Branch','branch_id');

		$result_c_j->addField('class_name','name');
		$result_c_j->addField('section');
		$result_j->addField('fees_name','name');
		$result->addCondition('fees_name','Caution Money');
		$result->_dsql()->group('student');

		if($_GET['branch']){
			$this->api->stickyGET('branch');
			$result->addCondition('branch_id',$_GET['branch']);
		}
		// else
		// 	$result->addCondition('id',-1);


		$grid->setModel($result,array('student','paid_amount','class_name','section','branch'));		
		$grid->addPaginator(50);	

		$grid->removeColumn('session');	
		$grid->removeColumn('branch');	

		if($form->isSubmitted()){

			$grid->js()->reload(array(
					'branch'=>$form['branch']
				))->execute();
		}
	}
}
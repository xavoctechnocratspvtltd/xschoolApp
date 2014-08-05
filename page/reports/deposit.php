<?php

class page_reports_deposit extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class=$this->api->currentBranch->classes();
		$class->title_field='full_name';
		$class_field->setModel($class);
		$month_field=$form->addField('dropdown','month')->setValueList(array('1'=>'Jan',
																			'2'=>'Feb',
																			'3'=>'March',
																			'4'=>'April',
																			'5'=>'May',
																			'6'=>'Jun',
																			'7'=>'July',
																			'8'=>'august',
																			'9'=>'Sep',
																			'10'=>'Oct',
																			'11'=>'Nov',
																			'12'=>'Dec')
																		)->setEmptyText('Please Select');

		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$receipt=$this->add('Model_FeesReceipt');

		$s_j=$receipt->join('students','student_id');
		$s_c_j=$s_j->join('classes','class_id');
		$s_c_j->addField('class_name','name');

		if($_GET['filter']){
			if($_GET['class']){
				$class=$this->add('Model_Class')->load($_GET['class']);
				$receipt->addCondition('class_name',$class['full_name']);
			}
		}
		$grid->setModel($receipt);



		$grid->addPaginator(50);

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class'],'month'=>$form['month'],'filter'=>1))->execute();
		}
	}
}
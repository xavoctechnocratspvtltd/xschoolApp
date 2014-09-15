<?php

class page_master_user_report extends Page{
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$staff_field=$form->addField('dropdown','staff')->setEmptyText('All');
		$staff_model=$this->add('Model_Staff');
		$staff_model->addCondition('is_active',true);
		$staff_field->setModel($staff_model);
		$form->addField('dropdown','month')->setValueList(array('1'=>'January',
            							'2'=>'February ',
            							'3'=>'March',
            							'4'=>'April',
            							'5'=>'May',
            							'6'=>'June',
            							'7'=>'July',
            							'8'=>'August',
            							'9'=>'September',
            							'10'=>'October',
            							'11'=>'November',
            							'12'=>'December'
            							))->setEmptyText('Please Select')->validateNotNull();

		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$staff=$this->add('Model_Staff');
		$staff->addCondition('is_active',true);
		$staff->addCondition('is_application_user',false);
		$staff_join=$staff->join('staff_attendances.staff_id','id');
		$staff_join->addField('attendence_on');
		$staff_join->addField('staff_id');

		$staff->addExpression('month')->set('month(attendence_on)');

		$staff->addExpression('total_present')->set(function($m,$q){
			return $m->refSQL('Staff_Attendance')->sum('is_present'); 
		});

		$staff->_dsql()->group('name');
		// $staff->_dsql()->order('name');

		if($_GET['filter']){
			$this->api->stickyGET('filter');
			$this->api->stickyGET('staff');
			$this->api->stickyGET('month');
			if($_GET['staff'])
				$staff->addCondition('staff_id',$_GET['staff']);
			elseif (!$_GET['staff']) {
				# code...
			}
			if($_GET['month'])
				$staff->addCondition('month',$_GET['month']);
		}else
			$staff->addCondition('id',-1);
		$grid->setModel($staff,array('name','total_present'));
		$grid->add('misc/Export');
		if($form->isSubmitted()){
			$grid->js()->reload(array('staff'=>$form['staff'],'month'=>$form['month'],'filter'=>1))->execute();
		}

	}
}
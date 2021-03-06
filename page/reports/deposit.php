<?php

class page_reports_deposit extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class=$this->api->currentBranch->classes();
		$class->title_field='full_name';
		$class_field->setModel($class);
		$month_field=$form->addField('dropdown','month')->setValueList(array('1'=>'January',
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
									            							))->setEmptyText('Please Select');

		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$receipt=$this->add('Model_FeesReceipt');

		$s_j=$receipt->join('students','student_id');
		$s_s_j=$s_j->join('scholars','scholar_id');
		$s_s_j->addField('scholar_no');
		$s_c_j=$s_j->join('classes','class_id');
	 	$receipt->addExpression('full_name')->set('(CONCAT('.$s_c_j->table_alias.'.name, " - ", '.$s_c_j->table_alias.'.section))');
	 	$receipt->addExpression('month')->set('(MONTH(created_at))');
	 	$receipt->addCondition('session_id',$this->api->currentSession->id);
		if($_GET['filter']){
			if($_GET['class']){
				$class=$this->add('Model_Class')->load($_GET['class']);
				$receipt->addCondition('full_name',$class['full_name']);
			}
			if($_GET['month']){

				$receipt->addCondition('month',$_GET['month']);
			}
		}else{
			$receipt->addCondition('id',-1);
		}


		$receipt->setOrder('created_at');

		$grid->add('H3',null,'top_1')->setHTML('Class ' . $class['full_name']);
		$grid->setModel($receipt,array('created_at','scholar_no','student','amount','Narration'));



		$grid->addPaginator(50);

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class'],'month'=>$form['month'],'filter'=>1))->execute();
		}

		$grid->setStyle('font-size','15px');

		$js=array(
			$this->js()->_selector('#header')->toggle(),
			$this->js()->_selector('#footer')->toggle(),
			$this->js()->_selector('ul.ui-tabs-nav')->toggle(),
			$this->js()->_selector('.atk-form')->toggle(),
			);

		$grid->js('click',$js);
	}
}
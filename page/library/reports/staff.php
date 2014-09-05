<?php

class page_library_reports_staff extends Page{
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$staff_field=$form->addField('autocomplete/Basic','staff');//->setEmptyText('Please Slecect');
		$staff=$this->add('Model_Staff');
		$staff->addCondition('is_active',true);
		$staff_field->setModel($staff);
		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');
		$form->addField('dropdown','type')->setValueList(array('Issue'=>'Issue','Submit'=>'Submit'))->validateNotNull();
		$form->addSubmit('GET LIST');

		$transaction=$this->add('Model_Library_Transaction');
		$transaction->addCondition('session_id',$this->api->currentSession->id);
		$grid=$this->add('Grid');
		if($_GET['filter']){
			if($_GET['staff'])
				$transaction->addCondition('staff_id',$_GET['staff']);
			if($_GET['type']=='Issue'){
				
				if($_GET['from_date'])
					$transaction->addCondition('issue_on','>=',$_GET['from_date']);
				if($_GET['to_date'])
					$transaction->addCondition('issue_on','<=',$_GET['to_date']);
			}elseif($_GET['type']=='Submit'){
				if($_GET['from_date'])
					$transaction->addCondition('submitted_on','>=',$_GET['from_date']);
				if($_GET['to_date'])
					$transaction->addCondition('submitted_on','<=',$_GET['to_date']);
			}

		}else
			$transaction->addCondition('id',-1);
		$grid->setModel($transaction,array('item','staff','branch','issue_on','submitted_on','narration'));

		if($form->isSubmitted()){
			$grid->js()->reload(array('staff'=>$form['staff'],'from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'type'=>$form['type'],'filter'=>1))->execute();
		}
	}
}
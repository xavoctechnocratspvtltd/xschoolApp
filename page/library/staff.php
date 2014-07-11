<?php
class page_library_staff extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$issue=$cols->addColumn(4);
		$submit=$cols->addColumn(4);
		$recent_activity=$cols->addColumn(4);
		$issue->add('H3')->set('Issue Library Items');
		$staff_model=$this->add('Model_Staff');
		$staff_model->addCondition('is_application_user',false);

		$item_model=$this->add('Model_Library_Item');
		$item_model->addCondition('is_issued',false);

		$issue_form=$issue->add('Form');
		$staff_field=$issue_form->addField('autocomplete/Basic','staffs');
		$staff_field->setModel($staff_model);
		$item_field=$issue_form->addField('autocomplete/Basic','item','Book / Issue Item');
		$item_field->setModel($item_model);
		$issue_form->addSubmit('Issue');

		if($issue_form->isSubmitted()){
			$staff=$this->add('Model_Staff');
			$staff->load($form['staff']);
			$item=$this->add('Model_Library_Item');
			$item->load($form['item']);
			if($staff->issue($item))
				$issue_form->js()->univ()->successMessage("Issued Successfully")->execute();

		}
			
	}
}
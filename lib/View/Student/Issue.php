<?php
class View_Student_Issue extends View {
	function init(){
		parent::init();

		$this->add('H3')->set('Issue Library Items');

		$student_model=$this->add('Model_Student');

		$item_model=$this->add('Model_Library_Item');
		$item_model->addCondition('is_issued',false);
		
		$issue_form=$this->add('Form');
		
		$student_field=$issue_form->addField('autocomplete/Basic','students')->validateNotNull();
		$student_field->setModel($student_model);

		
		
		$item_field=$issue_form->addField('autocomplete/Basic','item','Book / Issue Item')->validateNotNull();
		$item_field->setModel($item_model);
		
		$issue_form->addSubmit('Issue');

		if($issue_form->isSubmitted()){
			$student=$this->add('Model_Student');
			$student->load($issue_form['students']);
			$item=$this->add('Model_Library_Item');
			$item->load($issue_form['item']);
			$student->issue($item,$student);
				$issue_form->js(null,array($issue_form->js()->reload(),$issue_form->js()->_selector('.recentgrid')->trigger('reload')))->univ()->successMessage("Issued Successfully")->execute();

		}

	}
}
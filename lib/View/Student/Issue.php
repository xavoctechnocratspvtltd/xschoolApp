<?php
class View_Student_Issue extends View {
	function init(){
		parent::init();

		$this->add('H3')->set('Issue Library Items');

		$student_model=$this->add('Model_Student');

		$item_model=$this->api->currentBranch->library_items();
		$item_model->addCondition('is_issued',false);
		
		$issue_form=$this->add('Form');
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';

		$class_field=$issue_form->addField('dropdown','class')->setEmtyText('Please Select')->validateNotNull();
		$class_field->setModel($class_model);

		$student_field=$issue_form->addField('dropdown','students')->setEmtyText('Please Select')->validateNotNull();

		if($_GET['class'])
			$student_model->addCondition('class_id',$_GET['class']);
		else
			$student_model->addCondition('id',-1);
		$student_field->setModel($student_model);

		
		
		$item_field=$issue_form->addField('autocomplete/Basic','item','Book / Issue Item')->validateNotNull();
		$item_field->setModel($item_model);

		$class_field->js('change',$issue_form->js()->atk4_form('reloadField','students',array($this->api->url(),'class'=>$class_field->js()->val())));
		
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
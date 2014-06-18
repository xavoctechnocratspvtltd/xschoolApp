<?php

class page_master_student_list extends Page {
	
	function initMainPage(){

		$grid=$this->add('Grid');
		$current_student_model=$this->add('Model_CurrentStudent');
		
		if($_GET['remove_student']){
			$current_student_model->load($_GET['remove_student'])->deleteForced();
			$grid->js()->reload()->execute();
		}

		$grid->setModel($current_student_model);
		$grid->addClass('student-grid');
		$grid->js('reload')->reload();

		$grid->addColumn('Confirm','remove_student');
		$grid->addColumn('Expander','change_class');

	}

	function page_change_class(){
		
		$this->api->stickyGET('students_id');
		$this_student =$this->add('Model_Student')->load($_GET['students_id']);

		$branch_model=$this->add('Model_Branch');
		
		if($_GET['branch_id']){
			$this->api->stickyGET('branch_id');
			$branch_model->load($_GET['branch_id']);
		}else{
			$branch_model = $this_student->ref('class_id')->ref('branch_id');
		}

		$class_model = $branch_model->classes();
		$class_model->title_field='full_name';

		$class_model->addCondition('name',$this_student->ref('class_id')->get('name'));
		$class_model->addCondition('id','<>',$this_student['class_id']);

		$form=$this->add('Form');
		$branch_field=$form->addField('dropdown','branch')->setEmptyText('Please select')->validateNotNull();
		$branch_field->setModel($branch_model);
		$branch_field->set($this_student->ref('class_id')->get('branch_id'));


		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class_model);
		$class_field->setEmptyText('Please select')->validateNotNull();

		$form->addSubmit('Shift');

		$branch_field->js('change',$form->js()->atk4_form('reloadField','class',array($this->api->url(),'branch_id'=>$branch_field->js()->val())));

		if($form->isSubmitted()){
			$this_student->shiftToClass($this->add('Model_Class')->load($form['class']));
			$form->js(null,$form->js()->_selector('.student-grid')->trigger('reload'))->univ()->closeExpander()->execute();
		}

	}
}
<?php

class page_master_scholar_main extends Page {
	
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$scholar_model = $this->add('Model_Scholar');

		// $current_student_join = $scholar_model->leftJoin('students.scholar_id');
		// $current_student_join->hasOne('Session','session_id')->system(true);
		// $current_student_join->hasOne('Class','class_id','full_name')->system(true);

		$scholar_model->addExpression('current_class')->set(function($m,$q){
			$student_m = $m->add('Model_Student',array('table_alias'=>'cc'));
			$class_join = $student_m->join('classes','class_id');
			$student_m->addCondition('scholar_id',$q->getField('id'));
			$student_m->addCondition('session_id',$m->api->currentSession->id);

			return $student_m->_dsql()->del('fields')->field($student_m->dsql()->expr('concat(name," ",section)'));
		});


		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$new_scholar = $crud->add('Model_Scholar');
			// CreatNew Function call
			$new_scholar->createNew($form['name'],$form['father_name'],$other_fields=$form->getAllFields(),$form);
			if($form['enroll_in_class']){
				$class = $form->add('Model_Class')->load($form['enroll_in_class']);
				$class->addStudent($new_scholar, $form['student_type']);
			}

			return true; // Always required
		});

		if($crud->isEditing()){

		}else{
			// $scholar_model->getElement('session')->system(true);
		}

		if($crud->isEditing('add')){
			
			// Set class at the time of scholar admission
			$class_field = $crud->form->addField('DropDown','enroll_in_class')->setEmptyText('Do Not Enroll Now');
			$classes_for_current_branch = $this->api->currentBranch->classes();
			$classes_for_current_branch->title_field='full_name';
			$class_field->setModel($classes_for_current_branch);

			$scholar_model['scholar_no']= $scholar_model->getNewScholarNumber();

			// Set Student Type at the time of scholar addmission
			
			$student_type_field = $crud->form->addField('DropDown','student_type')->setEmptyText('Please Select Student Type');
			$student_type_field->validateNotNull();
			$student_type_field->setModel('StudentType');

		}

		if($crud->isEditing('edit')){
			$scholar_model->hook('editing');
		}

		$crud->setModel($scholar_model);


		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name','address','phone_no','scholar_no','father_name','cast','category'));

		}
	}
}
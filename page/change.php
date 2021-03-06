<?php

class page_change extends Page{
	function init(){
		parent::init();
		$this->add('View_Error')->set('Removed to Student Management > Student > Change Class');
		return;

		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);
		$form->addSubmit('GET LIST');

		$crud=$this->add('CRUD',array('allow_add'=>false,'allow_del'=>false));
		$marks=$this->add('Model_Student_Marks');
		$marks->getElement('class_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('student_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('exam_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('class_id')->getModel()->title_field='full_name';
		$marks_join=$marks->join('students','student_id');
		$s_marks_join=$marks_join->join('scholars','scholar_id');
		$s_marks_join->addField('scholar_no');
		
		if($_GET['class']){
			$this->api->stickyGET('class');
			if(!$crud->form)
				$marks->addcondition('class_id',$_GET['class']);
			
		}




		if(!$crud->isEditing()){
			$crud->grid->addPaginator(50);
			$crud->grid->addQuickSearch(array('scholar_no','class'));

			$crud->grid->addColumn('sno','sno');
			

		}
		$crud->setModel($marks,array('class_id'),array('student','class','scholar_no'));

		if($form->isSubmitted()){
			$crud->grid->js()->reload(array('class'=>$form['class']))->execute();
		}
	}
}
<?php

class page_change extends Page{
	function init(){
		parent::init();

		$crud=$this->add('CRUD',array('allow_add'=>false,'allow_del'=>fam_close(fam)));
		$marks=$this->add('Model_Student_Marks');
		$marks->getElement('class_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('student_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('exam_id')->display(array('form'=>'autocomplete/Basic'));
		$marks->getElement('class_id')->getModel()->title_field='full_name';
		$marks_join=$marks->join('students','student_id');
		$s_marks_join=$marks_join->join('scholars','scholar_id');
		$s_marks_join->addField('scholar_no');

		$crud->setModel($marks,array('class_id'),array('student','class','scholar_no'));


		if(!$crud->isEditing()){
			$crud->grid->addPaginator(50);
			$crud->grid->addQuickSearch(array('scholar_no'));
		}
	}
}
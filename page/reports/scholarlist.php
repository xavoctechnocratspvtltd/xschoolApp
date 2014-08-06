<?php

class page_reports_scholarlist extends Page{
	function init(){
		parent::init();

		$class=$this->add('Model_Class');
		$class->title_field='full_name';
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class);

		$form->addSubmit('GET LIST');

		$student_model=$this->add('Model_Student');
		$scholar_model=$student_model->Leftjoin('scholars','scholar_id');
		$scholar_model->addField('father_name');
		$scholar_model->addField('mother_name');
		$scholar_model->addField('blood_group');
		$scholar_model->addField('dob');
		$scholar_model->addField('phone_no');
		$scholar_model->addField('address');
		$scholar_model->addField('gender');
		$scholar_model->addField('category');
		$scholar_model->addField('admission_date');
		$scholar_model->addField('leaving_date');

		
		$grid=$this->add('Grid');
		if($_REQUEST[$class_field->name]){
			$this->api->stickyGET($class_field->name);
			$class->load($_REQUEST[$class_field->name]);
			$student_model->addCondition('class',$class['full_name']);
		}
		else {
			# code...
			// $student_model->addCondition('id',-1);
		}
		$grid->setModel($student_model,array('name','class','father_name','mother_name','phone_no','address','category','admission_date','leaving_date'));

		$grid->addPaginator(50);
		$grid->add('misc/Export');

		if($form->isSubmitted()){
			$grid->js()->reload(array($class_field->name=>$form['class']))->execute();

		}

	}
}
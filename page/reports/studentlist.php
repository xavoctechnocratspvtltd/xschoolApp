<?php

class page_reports_studentlist extends Page{
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_model=$this->add('Model_Class');
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);
		$form->addSubmit('GET LIST');

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(4);
		$grid=$col1->add('Grid');
		$student_model=$this->add('Model_Student');
		if($_GET['class']){
			$student_model->addCondition('class_id',$_GET['class']);
		}else{
			$student_model->addCondition('id',-1);
		}
		$grid->setModel($student_model,array('name'));

		$grid->addPaginator(50);

		// $js=array(
		// 		$this->js()->_selector('#header')->toggle(),
		// 		$this->js()->_selector('#footer')->toggle(),
		// 		$form->js()->toggle()
		// 	);

		// $grid->js('click',$js);
		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();
		}
	}
}
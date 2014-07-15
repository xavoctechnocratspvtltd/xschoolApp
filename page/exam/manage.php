<?php
class page_exam_manage extends Page {
	function init(){
		parent::init();

		$class=$this->api->currentBranch->classes();
		$class->title_field='full_name';
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$form->addSubmit('Filter');
		$class_field->setModel($class);

		$class_model=$this->api->currentBranch->classes();
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(8);
		$grid=$col1->add('Grid');
		if($_GET['class']){

			$class_model->addCondition('id',$_GET['class']);
			$class_model->tryLoadAny();
		}
			
		$grid->setModel($class_model,array('full_name'));

		$grid->addColumn('expander','exams');

		if($form->isSubmitted()){
			$grid->js()->reload(array('class'=>$form['class']))->execute();
		}
	}
}
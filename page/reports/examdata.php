<?php

class page_reports_examdata extends Page {
	function init() {
		parent::init();

		$form=$this->add( 'Form', null, null, array( 'form_horizontal' ) );
		$class_field=$form->addField( 'dropdown', 'class' );
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field = 'full_name';
		$class_field->setModel( $class_model );

		$terms_field= $form->addField('dropdown','term');
		$terms_field->setModel('Term');
		$terms_field->setEmptyText('Please Select');

		$form->addSubmit( 'GET LIST' );

		if($form->isSubmitted()){
			$form->js()->univ()->newWindow($this->api->url('reports_marks',array('class_id'=>$form['class'],'term_id'=>$form['term'])))->execute();
		}
	}
}

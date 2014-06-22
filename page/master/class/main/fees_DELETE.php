<?php

class page_master_class_main_fees extends \Page {
	function init(){
		parent::init();
		
		$this->api->stickyGET('classes_id');

		$class=$this->add('Model_Class');
		$class->load($_GET['classes_id']);

		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$fees_field=$form->addField('dropdown','fees')->setEmptyText('Please Select')->validateNotNull();
		$fees_field->setModel('Fees');
		$form->addSubmit('Add Fees');
		$grid=$this->add('Grid');

		if($form->isSubmitted()){
			
			$fees=$this->add('Model_Fees');
			$fees->load($form['fees']);

			$class->addFees($fees);
			$grid->js()->reload()->execute();
		}

		if($_GET['remove_fees']){
			$class->removeFees($this->add('Model_Fees')->load($_GET['remove_fees']));
			$grid->js()->reload()->execute();
		}

		$grid->setModel($class->allFees());

		$grid->addColumn('button','remove_fees');

	}
}
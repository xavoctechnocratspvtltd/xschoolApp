<?php
class page_master_student_list_consession extends Page {
	function init(){
		parent::init();


		$this->api->stickyGET('students_id');
		$this_student=$this->add('Model_Student')->load($_GET['students_id']);

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('line','consession');
		$form->addSubmit('Save');

		// Consessions given 
		$fees_transactions = $this->add('Model_FeesTransaction');
		$fees_transactions->addCondition('student_id',$this_student->id);
		$fees_transactions->addCondition('by_consession',true);

		$fees_transactions->_dsql()->del('fields')
			->field('submitted_on')
			->field('SUM(amount) as total_amount')
			->group('submitted_on');

		$grid = $this->add('Grid');
		$grid->setSource($fees_transactions->_dsql());
		$grid->addColumn('text','submitted_on','On Date');
		$grid->addColumn('text','total_amount');

		if($form->isSubmitted()){
			$this_student->payByConsession($form['consession']);

			$form->js()->univ()->closeExpander()->execute();
		}

	}
}
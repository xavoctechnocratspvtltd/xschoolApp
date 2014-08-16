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
			->field($fees_transactions->_dsql()->expr('submitted_on id'))
			->field('SUM(amount) as total_amount')
			->group('submitted_on');

		$grid = $this->add('Grid');

		if($_GET['remove']){
			
			$fees_transactions = $this->add('Model_FeesTransaction');
			$fees_transactions->addCondition('submitted_on',$_GET['remove']);
			$fees_transactions->deleteAll();
			$grid->js()->reload()->execute();
		}
		
		$grid->setSource($fees_transactions->_dsql());
		$grid->addColumn('text','submitted_on','On Date');
		$grid->addColumn('text','total_amount');
		$grid->addColumn('button','remove');



		if($form->isSubmitted()){
			try{
				$this->api->db->beginTransaction();
				$this_student->payByConsession($form['consession']);
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
				

			$form->js()->univ()->closeExpander()->execute();
		}

	}
}
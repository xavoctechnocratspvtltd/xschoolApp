<?php

class View_Student_FeesSubmitBlock extends View {

	public $student=null;

	function init(){
		parent::init();

		$student = $this->student;

		$this->addClass('fee_submit_block');
		$this->js('reload_me')->reload();

		if(!$student)
			throw $this->exception('Student is Not provided');

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(4);		
		$col2=$cols->addColumn(8);		

		// ======= COL 1
		$col1->add('H3')->set('Monthly Status');
		$grid=$col1->add('Grid');

		$fees_card=$student->getFeesCard();
		$grid->setSource($fees_card);

		$grid->addColumn('text','month');
		$grid->addColumn('text','total_fees');
		$grid->addColumn('text','paid_fees','Adjusted Fees');
		$grid->addColumn('Expander','details');
		
		// ======= COL 2
		
		$col2->add('H3')->setHTML($student['name']. ' <small>[ ' . $student->type()->get('name')." Class ".$student->ref('class_id')->get('full_name') .' ]</small>');
		$tabs = $col2->add('Tabs');
		$fast_deposit_tab = $tabs->addTab('Fast Deposit');
		$detailed_depot_tab = $tabs->addTab('Payment Details');
		$consession_details_tab = $tabs->addTab('Consesison Details');

		$fast_deposit_tab->add('View_Student_FastDeposit',array('student'=>$student));
		$detailed_depot_tab->add('View_Student_Receipts',array('student'=>$student));

		$fees_transactions = $this->add('Model_FeesTransaction');
		$fees_transactions->addCondition('student_id',$this->student->id);
		$fees_transactions->addCondition('by_consession',true);

		$fees_transactions->_dsql()->del('fields')
			->field('submitted_on')
			->field('SUM(amount) as total_amount')
			->group('submitted_on');

		$grid = $consession_details_tab->add('Grid');
		$grid->setSource($fees_transactions->_dsql());
		$grid->addColumn('text','submitted_on','On Date');
		$grid->addColumn('text','total_amount');

	}

}
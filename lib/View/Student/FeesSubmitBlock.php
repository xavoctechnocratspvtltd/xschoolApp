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
		$grid->addColumn('text','paid_fees');
		$grid->addColumn('Expander','details');
		
		// ======= COL 2
		
		$col2->add('H3')->setHTML($student['name']. ' <small>[ ' . $student->type()->get('name') .' ]</small>');
		$tabs = $col2->add('Tabs');
		$fast_deposit_tab = $tabs->addTab('Fast Deposit');
		$detailed_depot_tab = $tabs->addTab('Payment Details');

		$fast_deposit_tab->add('View_Student_FastDeposit',array('student'=>$student));
		$detailed_depot_tab->add('View_Student_Receipts',array('student'=>$student));



	}

}
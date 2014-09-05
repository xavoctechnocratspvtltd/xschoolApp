<?php

class page_reports_main extends Page {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('reports_student','Student Report');
		$tab1=$tabs->addTabUrl('reports_category','Category Wise Report');
		$tab1=$tabs->addTabUrl('reports_cast','Cast Wise Report');
		$tab1=$tabs->addTabUrl('reports_duefees','Defaulter List');
		$tab1=$tabs->addTabUrl('reports_scholar','Outgoing Student List');
		$tab1=$tabs->addTabUrl('reports_fees','Fees Collection Report');
		$tab1=$tabs->addTabUrl('reports_examdata','Exam Report');
		$tab1=$tabs->addTabUrl('reports_scholarlist','Scholar List');
		$tab1=$tabs->addTabUrl('reports_studentlist','Student List');
		$tab1=$tabs->addTabUrl('reports_consession','Consession List');
		$tab1=$tabs->addTabUrl('reports_deposit','Fees Deposit List');
		$tab1=$tabs->addTabUrl('reports_cheque','Cheque Payement');
	}
}
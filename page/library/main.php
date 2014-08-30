<?php

class page_library_main extends Page{
	function init(){
		parent::init();

		// $this->add('View_Error')->setHtml('<h3>Temporary In Testing Phase, No Activity Will be consider As Final Activity</h3>');
		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabURL('library_subjects','Subjects');
		$tab1=$tabs->addTabURL('library_title','Title');
		$tab1=$tabs->addTabURL('library_item','Item');
		// $tab1=$tabs->addTabURL('library_stocktransaction','Stock transaction');
		$tab1=$tabs->addTabURL('library_transaction','Library Actions');
		$tab1=$tabs->addTabURL('library_reports_staff','Staff Wise Report');
		$tab1=$tabs->addTabURL('library_reports_student','Student Wise Report');
		$tab1=$tabs->addTabURL('library_report','Genral Report');
		$tab1=$tabs->addTabURL('library_submit','Library Non Submit Item List');
	}
}
<?php

class page_reports_main extends Page {
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('reports_student','Student Report');
		$tab1=$tabs->addTabUrl('reports_category','Category Wise Report');
		$tab1=$tabs->addTabUrl('reports_duefees','Defaulter List');
		$tab1=$tabs->addTabUrl('reports_scholar','Outgoing Student List');
		$tab1=$tabs->addTabUrl('reports_fees','Fees Collection Report');
	}
}
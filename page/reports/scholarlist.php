<?php

class page_reports_scholarlist extends Page{
	function init(){
		parent::init();


		$grid=$this->add('Grid');
		$grid->setModel('Scholar');

		$grid->addPaginator(50);
		$grid->add('misc/Export');

	}
}
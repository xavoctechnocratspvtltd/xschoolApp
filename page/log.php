<?php
class page_log extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');
		$log=$this->add('Model_Log');
		$log->setOrder('id','desc');
		$grid->setModel($log);

		$grid->addPaginator(30);
		$grid->addQuickSearch(array('activity'));
	}
}
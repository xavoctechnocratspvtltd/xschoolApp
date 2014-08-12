<?php

class page_newreceipt extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');
	$fees=$this->add('Model_FeesReceipt');
	
	$fees->getElement('name')->display(array('grid'=>'grid/inline'));	
	$grid->setModel($fees);
	$grid->addPaginator(50);
	$fees->setOrder('id','desc');
	$grid->addQuickSearch(array('student','name'));
	}
}
<?php

class page_stock_main extends Page{
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('stock_category','Stock Category');
		$tab1=$tabs->addTabUrl('stock_item','Stock Item');
		$tab1=$tabs->addTabUrl('stock_supplier','Stock Supplier');
		$tab1=$tabs->addTabUrl('stock_inward','Stock Inward');
		$tab1=$tabs->addTabUrl('stock_consume','Stock Consume');
		$tab1=$tabs->addTabUrl('stock_issue','Stock Issue');
		$tab1=$tabs->addTabUrl('stock_submit','Stock Submit');
		$tab1=$tabs->addTabUrl('stock_current','Current Stock');
		$tab1=$tabs->addTabUrl('stock_staff','Staff wise Stock');
		$tab1=$tabs->addTabUrl('stock_genral','General Stock Report');
	}
}
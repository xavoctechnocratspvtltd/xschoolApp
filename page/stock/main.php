<?php

class page_stock_main extends Page{
	function init(){
		parent::init();

		$tabs=$this->add('Tabs');
		$tab1=$tabs->addTabUrl('stock_category','Stock Category');
		$tab1=$tabs->addTabUrl('stock_item','Stock Item');
		$tab1=$tabs->addTabUrl('stock_supplier','Stock Supplier');
		$tab1=$tabs->addTabUrl('stock_inward','Stock Inward');
		$tab1=$tabs->addTabUrl('stock_consume','Stock Consume');
	}
}
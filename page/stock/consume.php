<?php

class page_stock_consume extends Page{
	function init(){
		parent::init();

		$cols=$this->add('Columns');

		$col1=$cols->addColumn(4);
		$col2=$cols->addColumn(6);
		$col1->add('H4')->set('Consume Stock');
		$col2->add('H4')->set('Recent Consume Activity');

		$form=$col1->add('Form');
		$item_field=$form->addField('Dropdown','item')->setEmptyText('Please Select')->validateNotNull();
		$item_field->setModel('Stock_Item');

		$form->addField('line','qty')->validateNotNull();

		$form->addSubmit('Consume');


		$grid=$col2->add('Grid');
		$consume_model=$this->add('Model_Stock_Transaction');
		$consume_model->setOrder('id','desc');
		if($_GET['remove']){
			$consume_model->load($_GET['remove']);
			$consume_model->delete();
			$grid->js()->reload()->execute();

		}
		$consume_model->addCondition('type','Consume');
		$grid->setModel($consume_model,array('item','qty','created_at'));

		$grid->addColumn('button','remove');

		if($form->isSubmitted()){

			$item=$this->add('Model_Stock_Item');
			$item->load($form['item']);

			
			if($item->isAvailable($form['qty']))
				$form->displayError('qty','That Much Item is not available in stock');

			$item->consume($form['qty']);

			$form->js(null,array($grid->js()->reload(),$grid->js()->univ()->successMessage('Item Consume Successfully')))->reload()->execute();



		}
		
	}
}
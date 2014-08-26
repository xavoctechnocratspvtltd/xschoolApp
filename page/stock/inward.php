<?php

class page_stock_inward extends Page{
	function init(){
		parent::init();

		$cols=$this->add('Columns');

		$col1=$cols->addColumn(4);
		$col2=$cols->addColumn(6);
		$col1->add('H4')->set('Inward Stock');
		$col2->add('H4')->set('Recent Inward Activity');
		$form=$col1->add('Form');
		$supplier_field=$form->addField('dropdown','supplier')->setEmptyText('Please Select')->validateNotNull();
		$supplier_field->setModel('Stock_Supplier');
		$item_field=$form->addField('dropdown','item')->setEmptyText('Please Select')->validateNotNull();
		$item_field->setModel('Stock_Item');
		$form->addField('line','qty')->validateNotNull();
		$form->addField('line','rate')->validateNotNull();
		$form->addField('DatePicker','date')->validateNotNull();
		$form->addField('text','remark');

		$form->addSubmit('Inward');

		$grid=$col2->add('Grid');
		$inward_model=$this->add('Model_Stock_Transaction');
		$inward_model->addCondition('branch_id',$this->api->currentBranch->id);
		$inward_model->setOrder('id','desc');
		if($_GET['remove']){
			
			$inward_model->load($_GET['remove']);
			$inward_model->delete();
			$grid->js()->reload()->execute();


				

		}
		$inward_model->addCondition('type','Inward');

		$inward_model->getElement('issue_date')->system(true);
		$inward_model->getElement('submit_date')->system(true);
		$grid->setModel($inward_model);

		$grid->addColumn('button','remove');

		if($form->isSubmitted()){

			$item=$this->add('Model_Stock_Item');
			$item->load($form['item']);

			$supplier=$this->add('Model_Stock_Supplier');
			$supplier->load($form['supplier']);
			try{
				$this->api->db->beginTransaction();
				$item->inward($supplier,$form['qty'],$form['rate'],$form['date'],$form['remark']);
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}

			$form->js(null,array($grid->js()->reload(),$grid->js()->univ()->successMessage('Item Inward Successfully')))->reload()->execute();



		}
		

		

		
	}
}
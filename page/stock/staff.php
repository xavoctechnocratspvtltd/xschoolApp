<?php
class page_stock_staff extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$staff_field=$form->addField('autocomplete/Basic','staff');//->setEmptyText('Please Select');
		$staff=$this->add('Model_Staff');
		$staff->addCondition('is_active',true);
		$staff_field->setModel($staff);
		$form->addField('dropdown','type')->setValueList(array('Issue'=>'Issue','Submit'=>'Submit','Consume'=>'Consume'))->setEmptyText('Please Select');
		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$transaction=$this->add('Model_Stock_Transaction');
		if($_GET['filter']){
			$this->api->stickyGET('filter');
			$this->api->stickyGET('staff');
			$this->api->stickyGET('type');
			if($_GET['staff'])
				$transaction->addCondition('staff_id',$_GET['staff']);
			if($_GET['type'])
				$transaction->addCondition('type',$_GET['type']);
		}else{
			$transaction->addCondition('id',-1);
		}

		$grid->setModel($transaction);

		$grid->removeColumn('session');
		$grid->removeColumn('branch');
		$grid->removeColumn('supplier');
		$grid->add('misc/Export');

		if($form->isSubmitted()){
			$grid->js()->reload(array('staff'=>$form['staff'],'type'=>$form['type'],'filter'=>1))->execute();
		}
	}
}
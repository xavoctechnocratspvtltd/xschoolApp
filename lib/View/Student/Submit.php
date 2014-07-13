<?php
class View_Student_Submit extends View {
	function init(){
		parent::init();

		$this->add('H3')->set('Issue Library Items');

		

		$item_model=$this->add('Model_Library_Item');
		$item_model->addCondition('is_issued',true);

		$item_m_j=$item_model->join('library_transactions.item_id','id');
		$item_m_j->hasOne("Staff",'staff_id');
		$item_model->addCondition('staff_id',null);
		
		$form=$this->add('Form');
		$item_field=$form->addField('autocomplete/Basic','item','Book / Issue Item')
		->validateNotNull();
		
		
		
		$item_field->setModel($item_model);
		
		$form->addSubmit('Submit');

		$grid=$this->add('Grid');



		$transaction=$this->add('Model_Library_Transaction');


		if($_GET['item'])
			$transaction->addCondition('item_id',$_GET['item']);
		else
			$transaction->addCondition('item_id',-1);
		$grid->setModel($transaction,array('student','issue_on'));
		$grid->addColumn('button','submit_item');

		if($_GET['submit_item']){
			$transaction_model=$this->add('Model_Library_Transaction');

			$transaction_model->load($_GET['submit_item']);
			$transaction_model->submit();
			$grid->js(null,$grid->js()->_selector('.recentgrid')->trigger('reload'))->reload()->execute();
		}
		if($form->isSubmitted()){

			
			$grid->js()->reload(array('item'=>$form['item']))->execute();
		}

	}
}
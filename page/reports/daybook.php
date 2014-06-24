<?php

class page_reports_daybook extends Page {
	function init(){
		parent::init();

		$form= $this->add('Form');
		$form->addField('DatePicker','date');
		$form->addSubmit('DayBook');

		$day_transactions = $this->add('Model_PaymentTransaction');

		$grid= $this->add('Grid');

		$on_date = $this->api->today;
		
		if($_GET['date']){
			$on_date = $_GET['date'];
		}

		$day_transactions->addCondition('transaction_date','>=',$on_date);
		$day_transactions->addCondition('transaction_date','<',$this->api->nextDate($on_date));
		$day_transactions->addCondition('mode','<>','Cheque');

		

		$grid->setModel($day_transactions);
		$grid->addColumn('money','income');
		$grid->addColumn('money','expense');
		$grid->removeColumn('amount');
		$grid->removeColumn('transaction_type');

		$grid->addTotals(array('income','expense'));

		$grid->addHook('formatRow',function($grid){
			
			$grid->current_row['income'] = $grid->current_row['expense'] = '';

			if($grid->model['transaction_type']=='Income'){
				$grid->current_row['income'] = $grid->model['amount'];
				// $income_sum += $grid->current_row['income'];
			}else{
				$grid->current_row['expense'] = $grid->model['amount'];
				// $expense_sum += $grid->current_row['expense'];
			}
		});

		$grid->add('misc/Export');


		if($form->isSubmitted()){
			$grid->js()->reload(array('date'=>$form['date']?:0))->execute();
		}
	}
}
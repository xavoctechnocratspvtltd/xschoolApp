<?php

class page_reports_daybook extends Page {
	function init(){
		parent::init();

		$form= $this->add('Form');
		$form->addField('DatePicker','date');
		$form->addSubmit('DayBook');

		$day_transactions = $this->add('Model_PaymentTransaction');

		$grid= $this->add('MyGrid');

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

		$js=array(
				$this->js()->_selector('#header')->toggle(),
				$this->js()->_selector('#footer')->toggle(),
				$form->js()->toggle()
			);

		$grid->addMyTotals(array('income','expense'),'mode');
		$grid->js('click',$js);

		if($form->isSubmitted()){
			$grid->js()->reload(array('date'=>$form['date']?:0))->execute();
		}
	}
}
<?php

class page_reports_cheque extends Page {
	function init(){
		parent::init();

		$form= $this->add('Form',null,null,array('form_horizontal'));
		$form->addField('DatePicker','date');
		$form->addField('checkbox','only_fees_record');
		$form->addSubmit('GET LIST');

		$day_transactions = $this->add('Model_PaymentTransaction');
		$day_transactions->addCondition('branch_id',$this->api->currentBranch->id);
		$grid= $this->add('MyGrid')->addClass('mygrid');

		$on_date = $this->api->today;
		
		if($_GET['date']){
			$on_date = $_GET['date'];
		}

		$day_transactions->addCondition('transaction_date','>=',$on_date);
		$day_transactions->addCondition('transaction_date','<',$this->api->nextDate($on_date));
		$day_transactions->addCondition('mode','<>','Cash');
		if($_GET['only_fees_record']){
			$day_transactions->addCondition('fees_receipt_id','<>',null);
		}
		

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
			$grid->js()->reload(array('date'=>$form['date']?:0,'only_fees_record'=>$form['only_fees_record']?:0))->execute();
		}
	}
}
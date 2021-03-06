<?php

class page_reports_daybook extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$form= $this->add('Form');
		$form->addField('DatePicker','date');
		$form->addField('checkbox','only_fees_record');
		$form->addField('checkbox','include_cheque_fees');
		$form->addSubmit('DayBook');

		$day_transactions = $this->add('Model_PaymentTransaction');
		$day_transactions->addCondition('branch_id',$this->api->currentBranch->id);
		$grid= $this->add('MyGrid')->addClass('mygrid');

		$on_date = $this->api->today;
		
		if($_GET['date']){
			$on_date = $_GET['date'];
		}

		$day_transactions->addCondition('transaction_date','>=',$on_date);
		$day_transactions->addCondition('transaction_date','<',$this->api->nextDate($on_date));
		
		if($_GET['include_cheque_fees']){
		}else{
			$day_transactions->addCondition('mode','<>','Cheque');
		}
		
		
		if($_GET['only_fees_record']){
			$day_transactions->addCondition('fees_receipt_id','<>',null);
		}
		
		$grid->addMethod('format_bal',function($g,$f){
			$g->current_row_html[$f]=$g->current_row['income']-$g->current_row['expense'];
		});

		$grid->setModel($day_transactions);
		$grid->addColumn('money','income');
		$grid->addColumn('money','expense');
		// $grid->addColumn('bal','balance');
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
			$grid->js()->reload(array('date'=>$form['date']?:0,'only_fees_record'=>$form['only_fees_record']?:0,'include_cheque_fees'=>$form['include_cheque_fees']?:0))->execute();
		}
	}
}
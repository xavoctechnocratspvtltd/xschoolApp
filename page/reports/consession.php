<?php

class page_reports_consession extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');

		$form->addSubmit('GET LIST');

		$transaction=$this->add('Model_FeesTransaction');

		if($_GET['filter']){
			if($_GET['from_date'])
				$transaction->addCondition('submitted_on','>=',$_GET['from_date']);
			if($_GET['to_date'])
				$transaction->addCondition('submitted_on','<',$this->api->nextDate($_GET['from_date']));
		}else{
			//TODO
		}

		$transaction->_dsql()->group('student_id');
		$transaction->_dsql()->field('sum(amount) as t');
		$student_join = $transaction->join('students','student_id');

		$grid=$this->add('Grid');
		$grid->setSource($transaction->_dsql());
		$grid->addColumn('text','t');
		$grid->addPaginator(50);

		if($form->isSubmitted()){
			$grid->js()->reload(array('from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'filter'=>1))->execute();
		}
	}
}
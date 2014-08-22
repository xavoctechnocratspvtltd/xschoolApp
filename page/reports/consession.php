<?php

class page_reports_consession extends Page {
	function init(){
		parent::init();

		$transaction=$this->add('Model_FeesTransaction');

		$from_date = 'Start';
		$to_date = 'Last Entry';

		



		$form=$this->add('Form',null,null,array('form_horizontal'));
		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');

		$form->addSubmit('GET LIST');

		if($_GET['filter']){
			$this->api->stickyGET('filter');
			$this->api->stickyGET('from_date');
			$this->api->stickyGET('to_date');
			if($_GET['from_date']){
				$transaction->addCondition('submitted_on','>=',$_GET['from_date']);
				$from_date = $_GET['from_date'];
			}
			if($_GET['to_date']){
				$transaction->addCondition('submitted_on','<',$this->api->nextDate($_GET['to_date']));
				$to_date = $_GET['to_date'];
			}
		}else{
			//TODO
		}


		$transaction->_dsql()->field('sum(amount) as amount');
		$student_join = $transaction->join('students','student_id');
		$scholar_join = $student_join->join('scholars','scholar_id');
		$transaction->_dsql()->field($scholar_join->table_alias.'.name student_name');
		$transaction->_dsql()->field($scholar_join->table_alias.'.scholar_no scholar_no');
		$transaction->_dsql()->field('submitted_on');
		// $transaction->_dsql()->field('branch_id');
		$transaction->_dsql()->field('student_id');
		$transaction->_dsql()->where('by_consession=1');
		$transaction->_dsql()->group('submitted_on,student_id');

		$transaction->addCondition('branch_id',$this->api->currentBranch->id);

		$grid=$this->add('Grid');
				
		$grid->add('H1',null,'top_1')->set('Consession List '.$from_date .' to ' . $to_date);

		$grid->setSource($transaction->_dsql());
		$grid->addColumn('text','submitted_on');
		$grid->addColumn('text','scholar_no');
		$grid->addColumn('text','student_name');
		$grid->addColumn('text','amount');
		$grid->addPaginator(50);

		$js=array(
			$this->js()->_selector('#header')->toggle(),
			$this->js()->_selector('#footer')->toggle(),
			$this->js()->_selector('ul.ui-tabs-nav')->toggle(),
			$this->js()->_selector('.atk-form')->toggle(),
			);

		$grid->js('click',$js);

		if($form->isSubmitted()){
			$grid->js()->reload(array('from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'filter'=>1))->execute();
		}
	}
}
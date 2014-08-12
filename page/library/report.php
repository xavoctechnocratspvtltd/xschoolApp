<?php

class page_library_report extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));

		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');
		$form->addField('dropdown','type')->setValueList(array('Issue'=>'Issue','Submit'=>'Submit'))->setEmptyText('Please Select')->validateNotNull();
		$form->addSubmit('GET LIST');

		$grid=$this->add('Grid');
		$transaction=$this->add('Model_Library_Transaction');
		if($_GET['filter']){
			if($_GET['type']=='Issue'){
				if($_GET['from_date'])
					$transaction->addCondition('issue_on','>=',$_GET['from_date']);
				if($_GET['to_date'])
					$transaction->addCondition('issue_on','<=',$_GET['to_date']);
			}else{
				if($_GET['from_date'])
					$transaction->addCondition('submitted_on','>=',$_GET['from_date']);
				if($_GET['to_date'])
					$transaction->addCondition('submitted_on','<=',$_GET['to_date']);
			}

		}
		$grid->setModel($transaction);

		if($form->isSubmitted()){
			$grid->js()->reload(array('from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'type'=>$form['type'],'filter'=>1))->execute();
		}
	}
}
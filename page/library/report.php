<?php

class page_library_report extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));

		$form->addField('DatePicker','from_date')->validateNotNull();
		$form->addField('DatePicker','to_date')->validateNotNull();
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
			}elseif($_GET['type']=='Submit'){
				if($_GET['from_date'])
					$transaction->addCondition('submitted_on','>=',$_GET['from_date']);
				if($_GET['to_date'])
					$transaction->addCondition('submitted_on','<=',$_GET['to_date']);
			}

		}else
			$transaction->addCondition('id',-1);
		$grid->setModel($transaction);

		if($form->isSubmitted()){
			$grid->js()->reload(array('from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'type'=>$form['type'],'filter'=>1))->execute();
		}
	}
}
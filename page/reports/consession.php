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
			//TODO 
		}else{
			//TODO
		}

		$grid=$this->add('Grid');
		$grid->setModel($transaction);
		$grid->addPaginator(50);

		if($form->isSubmitted()){
			$grid->js()->reload(array('from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'filter'=>1))->execute();
		}
	}
}
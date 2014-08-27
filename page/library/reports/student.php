<?php

class page_library_reports_student extends Page{
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));

		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);

		$student_field=$form->addField('dropdown','students')->setEmptyText('Please Select');
		$form->addField('DatePicker','from_date');
		$form->addField('DatePicker','to_date');
		$form->addField('dropdown','type')->setValueList(array('Issue'=>'Issue','Submit'=>'Submit'))->setEmptyText('please select')->validateNotNull();
		$form->addSubmit('GET LIST');
		$student_model=$this->add('Model_CurrentStudent');
		if($_REQUEST[$class_field->name]){
			$this->api->stickyGET($_GET[$class_field->name]);
			$student_model->addCondition('class_id',$_REQUEST[$class_field->name]);
		}
		else{			
			$student_model->addCondition('id',-1);
		}
		$student_field->setModel($student_model);



		$transaction=$this->add('Model_Library_Transaction');
		$grid=$this->add('Grid');
		if($_GET['filter']){
			if($_GET['students'])
				$transaction->addCondition('student_id',$_GET['students']);
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
		$grid->setModel($transaction,array('item','student','branch','issue_on','submitted_on','narration'));

		$class_field->js('change',$form->js()->atk4_form('reloadField','students',array($this->api->url(),$class_field->name=>$class_field->js()->val())));

		if($form->isSubmitted()){
			// throw new Exception($form['type'], 1);
			
			$grid->js()->reload(array('students'=>$form['students'],'from_date'=>$form['from_date']?:0,'to_date'=>$form['to_date']?:0,'type'=>$form['type'],'filter'=>1))->execute();
		}
	}
}
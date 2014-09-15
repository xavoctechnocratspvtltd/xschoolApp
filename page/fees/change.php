<?php

class page_fees_change extends Page {
	function page_index(){
		// parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select');
		$class=$this->api->currentBranch->classes();
		$class->title_field='full_name';
		$class_field->setModel($class);	
		$student_field=$form->addField('dropdown','student')->setEmptyText('Please Select');
		$student=$this->add('Model_CurrentStudent');
		$student->addCondition('is_left',false);

		if($_REQUEST[$class_field->name]){
			$this->api->stickyGET($class_field->name);
			$class_field->set($_REQUEST[$class_field->name]);
			$student->addCondition('class_id',$_REQUEST[$class_field->name]);
		}

		else
			$student->addCondition('id',-1);
		$student_field->setModel($student);

		$month=$form->addField('dropdown','month')->setValueList(array('1'=>'Jan',
																		'2'=>'Feb',
																		'3'=>'March',
																		'4'=>'April',
																		'5'=>'May',
																		'6'=>'Jun',
																		'7'=>'July',
																		'8'=>'Agust',
																		'9'=>'Sept',
																		'10'=>'Oct',
																		 '11'=>'Nov',
																		 '12'=>'Dec'))
												->setEmptyText('Please Select');

		$form->addSubmit('GET LIST');

		$class_field->js('change',$form->js()->atk4_form('reloadField','student',array($this->api->url(),$class_field->name=>$class_field->js()->val()))
			
		);


		$grid=$this->add('Grid');
		$fees=$this->add('Model_StudentAppliedFees');
		$fees->addExpression('month')->set('month(due_on)');

		if($_REQUEST['filter']){
			$this->api->stickyGET('filter');
			$this->api->stickyGET('class_id');
			$this->api->stickyGET('student');
			$this->api->stickyGET('month');
			if($_GET['student'])
				$fees->addCondition('student_id',$_REQUEST['student']);
			if($_GET['month']){

				$fees->addCondition('month',$_REQUEST['month']);
			}
		}else
			$fees->addCondition('id',-1);

		$grid->setModel($fees,array('fees','amount','paid_amount','due_on'));
		$grid->addPaginator(50);

		$grid->addMethod('format_hide',function($g,$f){
			if($g->model['paid_amount']){
				$g->current_row_html['edit']='';
				$g->current_row_html['delete']='';
			}
		});

		if($_GET['delete']){
			$fees_app=$this->add('Model_StudentAppliedFees');
			$fees_app->load($_GET['delete']);
			$fees_app->deleteForced();

			$grid->js()->reload()->execute();

				
		}

		$grid->addColumn('expander,hide','edit');
		$grid->addColumn('confirm,hide','delete');

		$grid->addClass('mygrid');
		$grid->js('reload')->reload();

		if($form->isSubmitted()){

		$grid->js()->reload(array('filter'=>1,'student'=>$form['student'],'month'=>$form['month']))->execute();
		}
	}

	function page_edit(){
		$this->api->stickyGET('student_fees_applied_id');
		$fees_applied=$this->add('Model_StudentAppliedFees');
		$fees_applied->load($_GET['student_fees_applied_id']);
			
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$fees_field=$form->addField('dropdown','fees')->setEmptyText('Please Select');
		$fees_model=$this->add('Model_Fees');
		$fees_field->setModel($fees_model);
		$form->addField('line','amount');
		$form->addSubmit('Edit');
		if($form->isSubmitted()){
			$fees=$this->add('Model_Fees');
			$fees->load($form['fees']);

			$fees_applied->changeFees($fees,$form['amount']);
			$form->js(null,$form->js()->_selector('.mygrid')->trigger('reload'))->univ()->closeExpander()->execute();
		}
	}
}
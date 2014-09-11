<?php


class page_fees_deposit extends Page {

	function initMainPage(){
		
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$branch_field=$form->addField('dropdown','branch_id')->setEmptyText('Please Select');
		$class_field=$form->addField('dropdown','class_id')->setEmptyText('Please Select');
		$student_field=$form->addField('dropdown','student_id')->setEmptyText('Please Select');

		if($_REQUEST[$branch_field->name]){
			$this->api->stickyGET($branch_field->name);
			$branch=$this->add('Model_Branch');
			$branch->load($_REQUEST[$branch_field->name]);
		}else{
			$branch = $this->api->currentBranch;
		}

		$all_branches = $this->add('Model_Branch');
		$all_branches->addExpression('classes_count')->set(function($m,$q){
			return $m->refSQL('Class')->count();
		});

		$all_branches->addCondition('classes_count','>',0);

		$branch_field->setModel($all_branches);
		$branch_field->set($branch->id);
		
		$classes = $branch->classes();
		$classes->title_field='full_name';

		$class_field->setModel($classes);

		$class_model = $this->add('Model_Class');
		if($_REQUEST[$class_field->name]){
			$this->api->stickyGET($class_field->name);
			$class_model->load($_REQUEST[$class_field->name]);
		}else{
			$class_model=$classes->tryLoadAny();
		}

		$students=$class_model->students();

		$student_field->setModel($students);

		$scholar_no_field=$form->addfield('line','scholar_no');

		$form->addSubmit('Get Details');

		$branch_field->js('change',array(
				$form->js()->atk4_form('reloadField','class_id',array($this->api->url(),$branch_field->name=>$branch_field->js()->val()),$class_field->js()->trigger('change')->_enclose()),
			)
		);
		$class_field->js('change',$form->js()->atk4_form('reloadField','student_id',array($this->api->url(),$class_field->name=>$class_field->js()->val())));

		$deposit_container= $this->add('View');
		if($_REQUEST[$student_field->name] or $_REQUEST[$scholar_no_field->name]){
			$this->api->stickyGET($student_field->name);
			$this->api->stickyGET($scholar_no_field->name);
			$student=$this->add('Model_CurrentStudent');

			if($_REQUEST[$scholar_no_field->name]){
				$student->addCondition('scholar_no',$_REQUEST[$scholar_no_field->name])->tryLoadAny();
				if(!$student->loaded())
					$form->displayError($scholar_no_field->short_name,'Not Found');
			}
			else{
				$student->load($_REQUEST[$student_field->name]);
			}
			$deposit=$deposit_container->add('View_Student_FeesSubmitBlock',array('student'=>$student));
			$deposit_container->js(true)->show();
		}

		if($form->isSubmitted()){
			$st=$this->add('Model_CurrentStudent');

			if($st->isLeft($form['scholar_no']))
				throw new Exception("This Student Left in current Session");
				
			$deposit_container->js()->reload(array($student_field->name=>$form['student_id'], $scholar_no_field->name=>$form['scholar_no']))->execute();
		}

		$branch_field->js('change',$deposit_container->js()->hide());
		$class_field->js('change',$deposit_container->js()->hide());
		$student_field->js('change',array(
				$deposit_container->js()->hide(),
				$scholar_no_field->js()->val('')
			)
		);
		$scholar_no_field->js('change',$deposit_container->js()->hide());

	}

	function page_details(){
		$get =$_GET['_id'];
		$data =json_decode($get,true);

		$student = $this->add('Model_CurrentStudent')->load($data['student_id']);
		$applied_feeses = $student->appliedFees();
		$applied_feeses->addCondition('due_on','>=',date('Y-m-01',strtotime($data['date'])));
                $applied_feeses->addCondition('due_on','<=',date('Y-m-t',strtotime($data['date'])));
		$applied_feeses->setOrder('id');

		$array=array();

		foreach ($applied_feeses as $junk) {
			$array[] =array(
					'fees'=>$applied_feeses['fees'],
					'amount'=>$applied_feeses['amount'],
					'paid'=>$applied_feeses->paidAmount()
				);
		}

		$grid =$this->add('Grid');
		$grid->setSource($array);

		$grid->addColumn('text','fees');
		$grid->addColumn('text','amount');
		$grid->addColumn('text','paid');

	}
}


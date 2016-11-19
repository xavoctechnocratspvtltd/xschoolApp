<?php

class page_master_student_list extends Page {
	
	function initMainPage(){

		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field="full_name";
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class_model);

		$form->addSubmit('Get List');

		$grid=$this->add('Grid');
		$current_student_model=$this->add('Model_CurrentStudent');
		$current_student_model->addCondition('is_left',0);

		// if($_GET['mark_left']){
		// 	$this->api->stickyGET('mark_left');
		// 	$all_available_student=$this->add('Model_Student');
		// 	$all_available_student->load($_GET['mark_left']);
		// 	$all_available_student->markLeft();
		// 	$grid->js()->reload()->execute();
		// }
		
		if($_GET['remove_student']){
			// $this->api->stickyGET('remove_student');
			try{
				$this->api->db->beginTransaction();
				$current_student_model->load($_GET['remove_student'])->deleteForced();
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
			}
				
			$grid->js(true)->reload()->execute();
			exit;
		}

		if($_GET['class_id']){
			$current_student_model->addCondition('class_id',$_GET['class_id']);
		}
		else{
			$current_student_model->addCondition('id',-1);
		}

		$grid->setModel($current_student_model);
		$grid->addClass('student-grid');
		$grid->js('reload')->reload();

		// $grid->addColumn('button','mark_left');
		$grid->addColumn('Confirm','remove_student');
		$grid->addColumn('Expander','fees_applied');
		$grid->addColumn('Expander','change_class');
		$grid->addColumn('Expander','consession');
		$grid->addPaginator(50);
		$grid->addQuickSearch(array('scholar','scholar_no','class','roll_no'));
		// 
		$grid->removeColumn('name');


		if($form->isSubmitted()){
			$grid->js()->reload(array('class_id'=>$form['class']))->execute();
		}

	}


	function page_fees_applied(){
		$this->api->stickyGET('students_id');
		$this_student =$this->add('Model_Student')->load($_GET['students_id']);

		$feeses = $this->add('Model_Fees');

		foreach ($feeses as $fee) {
			$btn = $this->add('Button')->set($feeses['name']);
			if($fee_applied = $this_student->hasFeesApplied($feeses)){
				$btn->addClass('btn btn-success');
			}else{
				$btn->addClass('btn btn-danger');		

			}

			if($btn->isClicked("Are you sure")){
				try{
					if($fee_applied){
						$this_student->removeFees($feeses);
					}else{
						$this_student->addFees($feeses);
					}
				}catch(Exception $e){
					$this->api->db->rollBack();
					throw $e;
				}
					
				$btn->js()->reload()->execute();
			}

		}


	}

	function page_change_class(){
		
		$this->api->stickyGET('students_id');
		$this_student =$this->add('Model_Student')->load($_GET['students_id']);

		// $branch_model=$this->add('Model_Branch');
		
		// if($_GET['branch_id']){
		// 	$this->api->stickyGET('branch_id');
		// 	// throw new Exception($_GET['branch_id'], 1);
			
		// 	$branch_model->load($_GET['branch_id']);
		// }else{
		// 	$branch_model = $this_student->ref('class_id')->ref('branch_id');
		// }

		$class_model = $this->add('Model_Class');
		$class_model->title_field='full_name';

		$class_model->addCondition('name',$this_student->ref('class_id')->get('name'));
		$class_model->addCondition('id','<>',$this_student['class_id']);

		$form=$this->add('Form');
		// $branch_field=$form->addField('dropdown','branch')->setEmptyText('Please select')->validateNotNull();
		// $branch_field->setModel($branch_model);
		// $branch_field->set($this_student->ref('class_id')->get('branch_id'));


		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class_model);
		$class_field->setEmptyText('Please select')->validateNotNull();

		$form->addSubmit('Shift');


		// $branch_field->js('change',$form->js()->atk4_form('reloadField','class',array($this->api->url(),'branch_id'=>$branch_field->js()->val())));
		if($form->isSubmitted()){
			try{
				$this->api->db->beginTransaction();
				$this_student->shiftToClass($this->add('Model_Class')->load($form['class']));
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
			}
				
			$form->js(null,$form->js()->_selector('.student-grid')->trigger('reload'))->univ()->closeExpander()->execute();
		}

	}
}
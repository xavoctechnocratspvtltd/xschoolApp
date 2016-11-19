<?php
class View_Staff_Issue extends View {
	function init(){
		parent::init();

		$this->add('H3')->set('Issue Library Items');
		$staff_model=$this->add('Model_Staff');
		// $staff_model=$this->api->currentBranch->staffs();
		$staff_model->addCondition('is_application_user',0);
		// $staff_model->addCondition('is_active',0);

		$item_model=$this->api->currentBranch->library_items();
		// $item_model=$this->add('Model_Library_Item');
		// $item_model->addCondition('branch_id',$this->api->currentBranch->id);
		$item_model->addCondition('is_issued',false);
		// $item_model->title_field='full_name';
		$issue_form=$this->add('Form');
		
		$staff_field=$issue_form->addField('autocomplete/Basic','staffs')->validateNotNull();
		$staff_model->addCondition('is_active',true);
		$staff_field->setModel($staff_model);

		
		
		$item_field=$issue_form->addField('autocomplete/Basic','item','Book / Issue Item')->validateNotNull();
		$item_field->setModel($item_model);
		

		$issue_form->addSubmit('Issue');

		if($issue_form->isSubmitted()){
			$staff=$this->add('Model_Staff');
			$staff->load($issue_form['staffs']);
			$item=$this->add('Model_Library_Item');
			$item->load($issue_form['item']);
			try{
				$this->api->db->beginTransaction();
				$staff->issue($item,null,$staff);
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
			}
				
				
				$issue_form->js(null,array($issue_form->js()->reload(),$issue_form->js()->_selector('.recentgrid')->trigger('reload')))->univ()->successMessage("Issued Successfully")->execute();

		}

	}
}
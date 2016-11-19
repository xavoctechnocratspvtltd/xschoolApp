<?php

class page_sms_transportation extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(6);
		$form=$col1->add('Form');
		$class_field=$form->addField('dropdown','class')->setEmptyText('Please Select')->validateNotNull();
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);
		$form->addField('text','message')->validateNotNull();
		$form->addSubmit('Send');

		if($form->isSubmitted()){
			$numbers=array();
			$class=$this->add('Model_Class');
			$class->load($form['class']);
			foreach ($st=$class->students()->addCondition('vehicle_id','<>',null) as $key => $value) {
				if(!$st['phone_no']) continue;
				$numbers[]=$st['phone_no'];
			}
		

			$sms=$this->add('Model_Sms');
			try{
				$this->api->db->beginTransaction();
				$sms->sendMessage($form['message'],$numbers,null);
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
			$form->js()->reload(null,$form->js()->univ()->successMessage('Message Send Successfully'))->execute();
		}
		
	}
}
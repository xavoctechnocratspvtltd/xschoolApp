<?php

class page_sms_staff extends Page {
	function init(){
		parent::init();

		$cols=$this->add('Columns');
		$col1=$cols->addColumn(6);
		$form=$col1->add('Form');
		$form->addField('text','message')->validateNotNull();
		$form->addSubmit('Send');

		if($form->isSubmitted()){
			$numbers=array();
			$st=$this->add('Model_Staff');
			$st->addCondition('is_active',true);
			$st->addCondition('is_application_user',false);

			foreach ($st as $key => $value) {
				if(!$st['mobile_no']) continue;
				$numbers[]=$st['mobile_no'];

			}
		
			// print_r($numbers);
			$sms=$this->add('Model_Sms');
			try{
				$this->api->db->beginTransaction();
				$sms->sendMessage($form['message'],$numbers,null);
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
			$form->js()->reload(null,$form->js()->univ()->successMessage('Message Send Successfully'))->execute();
		}
		
	}
}
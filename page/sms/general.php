<?php

class page_sms_general extends Page {
	function init(){
		parent::init();
		$cols=$this->add('Columns');
		$col1=$cols->addColumn(6);
		$form=$col1->add('Form');
		$form->addField('text','numbers')->validateNotNull();
		
		$form->addField('text','message')->validateNotNull();
		$form->addSubmit('Send');

		if($form->isSubmitted()){
			// $class->sendMessage($text,)
			$sms=$this->add('Model_Sms');
			try{

				$this->api->db->beginTransaction();
				$sms->sendMessage($form['message'],$form['numbers']);
				$this->api->db->commit();
			}catch(Exception $e){

				$this->api->db->rollBack();
				throw $e;
				
			}
			$form->js()->reload(null,$form->js()->univ()->successMessage('Message Send Successfully'))->execute();
		}
	}
}
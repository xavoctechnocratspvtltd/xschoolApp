<?php

class Model_Sms extends Model_Table {
	var $table= "sms";
	function init(){
		parent::init();

		$this->hasOne('Class','class_id');
		$this->addField('numbers')->type('text');
		$this->addField('message')->type('text');
		$this->addField('created_at')->type('datetime')->defaultValue($this->api->now);
		$this->addHook('afterInsert',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function afterInsert($obj,$new_id){
		$sms=$this->add('Model_Sms');
		$sms->load($new_id);

		$numbers=array();


		if($sms['class_id']){
			$class=$this->add('Model_Class');
			$class->load($sms['class_id']);
			foreach ($class->ref('Student') as $junk) {
				$numbers[]=trim($junk['ph_no']);
			}
		}
		elseif($sms['numbers']){
			$no=explode(',', $sms['numbers']);
			foreach ($no as $junk) {
				$numbers[]=trim($junk['ph_no']);
			}
		}else
			throw new Exception("Required Proper Data", 1);
			
		foreach ($numbers as $number) {
			$this->sendSMS($number,$sms['message']);
		}




	}

	function createNew($message,$numbers=null,$class=null){
		if($this->loaded())
			throw new Exception("Please Call on empty Object");
		if($class){
				if(!($class instanceof Model_Class) and !$class->loaded())
					throw new Exception("Please Pass loaded object of class");
				$this['class_id']=$class->id;
			}

		if(!$message)
			throw new Exception("Please specify the message");

		if(is_array($numbers))
			$numbers = implode(",", $numbers);
		
		$this['message']=$message;
		$this['numbers']=$numbers;
		$this->save();		

		$log=$this->add('Model_Log');
		$log->createNew("sms entry created");
		$log->save();	
	}

	function sendMessage($message,$numbers=null,$class=null){
		// echo "<pre>";
		// print_r($numbers);
		// echo "</pre>";
		$this->createNew($message,$numbers,$class);
	}

	function sendSMS($on_number=null){
		// $this['update_code']="SB24-".rand(10000,99999);
		// $this['code_valid_till']=date("Y-m-d",strtotime("+1 day"));
		// $this->save();

		if($on_number)
			$no=$on_number;
		// else
		// 	$no=$this['numberr'];
		$this->add('Controller_Sms')->sendMessage($no, $this['message']);

		$log=$this->add('Model_Log');
		$log->createNew("sms send");
		$log->save();

	}
}
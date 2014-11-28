<?php

class Model_Sms extends Model_Table {
	var $table= "sms";
	function init(){
		parent::init();

		$this->hasOne('Branch','branch_id')->defaultValue($this->api->currentBranch->id);
		$this->hasOne('Session','session_id')->defaultValue($this->api->currentSession->id);
		$this->hasOne('Class','class_id');
		$this->addField('numbers')->type('text');
		$this->addField('message')->type('text');
		$this->addField('created_at')->type('date')->defaultValue($this->api->today);
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
				$numbers[]=trim($junk['phone_no']);
			}
		}
		elseif($sms['numbers']){
			$no=explode(',', $sms['numbers']);
			foreach ($no as $junk) {
				$numbers[]=trim($junk['phone_no']);
			}
		}else
			throw new Exception("Required Proper Data", 1);
			
		foreach ($numbers as $number) {
			$number_s = $this->senitizeNumber($number);
			if(count($number_s))
				echo $number." =>". print_r($number_s,true) .'<br/>';
				$this->sendSMS($number,$sms['message']);
		}

	}

	function senitizeNumber($number){
		// Check situations like
		// 234564 9783807100
		// 9783807100, 9782300801
		// 9782300801 234543, 9782300801
		
		// replace space with comma
		// explode with comma
		// foreach loop
		// if length==10 or 11 return number
		// else continue
		// at last return false
		
		$ok_numbers=array();

		$number_single_space = preg_replace("/\s+/", ' ', $number);
		$number_orig=str_replace(' ', ',', $number_single_space);
		$numbers_arr=explode(',', $number_orig);
		foreach ($numbers_arr as $n) {
			$n=trim($n);
			if(strlen($n) == 10 or strlen($n)==11)
				$ok_numbers[] = $n;
		}
		return $ok_numbers;
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
		// throw new Exception("Error Processing Request", 1);
		
		$this->createNew($message,$numbers,$class);
	}

	function sendSMS($on_number=null){
		// $this['update_code']="SB24-".rand(10000,99999);
		// $this['code_valid_till']=date("Y-m-d",strtotime("+1 day"));
		// $this->save();

		if($on_number)
			$no=$on_number;
		else
			$no=$this['numberr'];
		$this->add('Controller_Sms')->sendMessage($no, $this['message']);

		$log=$this->add('Model_Log');
		$log->createNew("sms send");
		$log->save();

	}
}
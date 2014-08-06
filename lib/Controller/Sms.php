<?php

class Controller_Sms extends AbstractController{
	function sendActivationCode($model,$code){

	}

	function sendMessage($no,$name='',$code=''){
		$curl=$this->add('Controller_CURL');
		$msg="Dear $name, Welcome to sabkuch24.com.Your confirmation code is $code";
		$msg=urlencode($msg);
		$url="http://sms.alakh.co/sendsms?uname=sabkuch24&pwd=sabkuch24&senderid=SBKUCH&to=$no&msg=$msg&route=T";
		$curl->get($url);
	}
}
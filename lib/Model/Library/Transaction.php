<?php

class Model_Library_Transaction extends Model_Table{
	public $table="library_transactions";
	function init(){
		parent::init();
		

		$this->hasOne('Library_Item','item_id');
		$this->hasOne('Student','student_id');
		$this->hasOne('Staff','staff_id');

		// $this->addField('name');
		$this->addField('issue_on')->type('date')->defaultValue($this->api->today);
		$this->addField('submitted_on')->type('date');
		$this->addField('narration')->type('text');
		$this->addField('no_of_day_late_submission');

		$this->add('dynamic_model/Controller_AutoCreator');

	}

	function issue($issue_on,$item,$student=null,$staff=null,$narration=null,$form=null){
		if($this->loaded())
			throw $this->exception('Please Provide Empty Model of transaction');
		if(!$staff->loaded())
			throw $this->exception('Please Provide Loaded Model of staff');
		if(!$student->loaded())
			throw $this->exception('Please Provide Loaded Model of student');
		if(!$item->loaded())
			throw $this->exception('Please Provide Loaded Model of item');
		if(!$staff instanceof Model_Staff)
			throw $this->exception('You can not pass staff model object please check ');
		if(!$staff instanceof Model_Student)
			throw $this->exception('You can not pass student model object please check ');
		if(!$item instanceof Model_Item)
			throw $this->exception('You can not pass student model object please check ');

		if($staff==null and $student==null)
			$this->api->js()->errorMessage('Either Put Roll No or Specify Staff')->execute();
		$narration.=" Issued".$this['name'].' On '.$this['issue_on'];
		$this['student_id']=$student->id;
		$this['staff_id']=$staff->id;
		$this['issue_on']=$issue_on;
		$this['item_id']=$item->id;
		$this['narration']=$narration;
		$this->save();
		return true;
	}

	function submit($item){
		if(!$this->isIssued($item))
			throw $this->exception('This Item Does not issued, Please Check');
		if(!$this->isIssued($item))
			throw $this->exception('Not Item Issued');

		$submitted_on = strtotime($this->api->getConfig('school/submitted_in_days'),strtotime($this['submitted_on']));
		$date_array=$this->api->my_date_diff($submitted_on,$this->api->today);
		if($date_array['days_total']>0)
			$this['no_of_day_late_submission']=$date_array['days_total'];
		$this['submitted_on']=$this->api->today;
		$this->save();
		return true;

	}

	function isIssued($item){
		if(!$this->loaded())
			throw $this->exception('Please Call on loaded transaction ');
		if(!$item->loaded())
			throw $this->exception('Please pass loaded model of item ');
		$this->addCondition('submitted_on',null);
		$this->addCondition('item_id',$item->id);
		$this->tryLoadAny();
		if($this->loaded())
			return $this;
		else 
			return false;


	}
}	
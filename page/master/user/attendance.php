<?php

class page_master_user_attendance extends Page{
	
	function init(){
		parent::init();
		$form=$this->add('Form');
		$form->addField('DatePicker','for_date')->validateNotNull();
		$form->addSubmit('Get Attendance');

		$grid=$this->add('Grid');
		$attendance = $this->add('Model_Staff_Attendance');
		// $attendance->debug();
		


		$count_present = $this->add('Model_Staff_Attendance');
		$count_present->addCondition('is_present',true);

		if($_GET['for_date']){
			
			$this->api->stickyGET('for_date');
			if($this->add('Model_Staff')->count()->getOne() != $this->add('Model_Staff_Attendance')->addCondition('attendence_on',$_GET['for_date'])->count()->getOne() ){
				foreach($s=$this->add('Model_Staff') as $junk){
					$attendance_temp= $this->add('Model_Staff_Attendance');
					$attendance_temp->addCondition('staff_id',$s->id);
					$attendance_temp->addCondition('attendence_on',$_GET['for_date']);
					$attendance_temp->tryLoadAny();
					if(!$attendance_temp->loaded()){
						$attendance_temp->save();
					}
				}
			}
			$attendance->addCondition('attendence_on',$_GET['for_date']);
		}else{
			$attendance->addCondition('attendence_on',date('Y-m-d'));
		}
		




		$grid->setModel($attendance);
		$grid->addColumn('Button','swap_present');

		if($_GET['swap_present']){
			$att = $this->add('Model_Staff_Attendance');
			if($_GET['for_date'])
				$att->addCondition('attendence_on',$_GET['for_date']);
			else
				$att->addCondition('attendence_on',date('Y-m-d'));
			$att->load($_GET['swap_present']);
			$att->swapPresent();
			$grid->js()->reload()->execute();	
		}

		if($form->isSubmitted()){
			$grid->js()->reload(array('for_date'=>$form->get('for_date')))->execute();
		}
	}
}
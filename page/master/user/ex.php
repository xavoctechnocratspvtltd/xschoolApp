<?php
 
class page_master_user_ex extends Page{
	function init(){
		parent::init();

		$grid=$this->add('Grid');
		$deactive_staff=$this->add('Model_Staff');
		$deactive_staff->addCondition('is_active',false);
		$grid->setModel($deactive_staff);

		if($_GET['active']){

			$staff=$this->add('Model_Staff');
			$staff->load($_GET['active']);
			$staff->active();
			$grid->js(null,$grid->js()->univ()->successMessage('Staff Active successfully'))->reload()->execute();
			
		}


		$grid->addColumn('button','active');
		$grid->add('misc/Export');

	}
}
<?php
class View_Staff_Recent extends View {
	function init(){
		parent::init();

		$this->add('H3')->set('Recent Activities');
		$grid=$this->add('Grid')->addClass('recentgrid');
		$grid->js('reload')->reload();
		
		$transaction=$this->add('Model_Library_Transaction');
		$transaction->join('staffs','staff_id');
		$transaction->addCondition('branch_id',$this->api->currentBranch->id);
		$transaction->setOrder('id','desc');

		$grid->setModel($transaction,array('staff','item','issue_on','submitted_on'));
		$grid->addPaginator(10);

	}
}
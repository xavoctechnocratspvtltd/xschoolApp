<?php

class page_correcttransaction extends Page {
	function init(){
		parent::init();

		// Without bellow code you will get thrown on first page every time you change branch
		$this->api->stickyGET('s_correcttransaction_grid_paginator_skip');
		$this->js(true)->_selector('#header')->hide();
		$grid=$this->add('Grid');
		$payment=$this->add('Model_PaymentTransaction');
		$grid->setModel($payment);
		$grid->addPaginator(20);
		$branch=$this->add('Model_Branch');

		$grid->addColumn('button','swami_nagar');
		$grid->addColumn('button','rk_puram');
	
		$payment_model=$this->add('Model_PaymentTransaction');
		if($_GET['swami_nagar']){
			$payment_model->load($_GET['swami_nagar']);
			$payment_model['branch_id']=$this->add('Model_Branch')->addCondition('name','Swami Nagar')->tryLoadAny()->get('id');
			$payment_model->save();
			$grid->js()->reload()->execute();
		}

		if($_GET['rk_puram']){
			$payment_model->load($_GET['rk_puram']);
			$payment_model['branch_id']=$this->	add('Model_Branch')->addCondition('name','RK Puram')->tryLoadAny()->get('id');
			$payment_model->save();
			$grid->js()->reload()->execute();
		}
	}

}
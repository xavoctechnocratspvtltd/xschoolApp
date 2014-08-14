<?php

class page_transport_studentlist extends Page {
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$vehicle_field=$form->addField('dropdown','vehicle');
		$vehicle_field->setModel($this->api->currentBranch->vehicle());
		$form->addSubmit('GET LIST');
		$vehicle=$this->add('Model_Vehicle');
		if($_GET['vehicle']){

			$vehicle->addCondition('id',$_GET['vehicle']);
			$vehicle->tryLoadAny();
		}
		
		$grid=$this->add('Grid');
		$grid->add('H3',null,'top_1')->setHTML('vehicle ' . $vehicle['name'] . '<small> Driver Name '.$vehicle['driver_name'].'Driver Number'.$vehicle['driver_number'].'</small>');

		$students=$this->add('Model_Student');
		$s_j_v=$students->join('vehicles','vehicle_id');
		$s_j_v->addField('driver_name');
		$s_j_v->addField('driver_number');



		if($_GET['vehicle'])
			$students->addCondition('vehicle_id',$_GET['vehicle']);
		else
			$students->addCondition('id',-1);
		$grid->setStyle('font-size','15px');
		$grid->setModel($students,array('name','class','scholar_no'));

		// $h->set("Driver Name ".$students['driver_number']);

		if($form->isSubmitted()){
			$grid->js()->reload(array('vehicle'=>$form['vehicle']))->execute();
		}


		$js=array(
			$this->js()->_selector('#header')->toggle(),
			$this->js()->_selector('#footer')->toggle(),
			$this->js()->_selector('ul.ui-tabs-nav')->toggle(),
			$this->js()->_selector('.atk-form')->toggle(),
			);

		$grid->js('click',$js);
	}
}
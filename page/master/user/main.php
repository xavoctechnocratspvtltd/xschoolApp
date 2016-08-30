<?php

class page_master_user_main extends Page {
	function page_index(){
		// parent::init();
	
		$crud=$this->add('xCRUD');

		$staff_model_new=$this->add('Model_Staff');
		$staff_model_new->addCondition('is_active',true);

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$staff_model = $crud->add('Model_Staff');
			// CreatNew Function call
			try{
				$crud->api->db->beginTransaction();
				$staff_model->createNew($form['name'],$form['username'],$form['password'],$form->getAllFields(),$form);
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			return true; // Always required
		});

		if($_GET['deactive']){
			$old_staff=$this->add('Model_Staff');
			$old_staff->load($_GET['deactive']);
			$old_staff->deactive();
			if($crud->grid)
				$crud->grid->js(null,$crud->grid->js()->univ()->successMessage('Staff deactive successfully'))->reload()->execute();
		}
		
		$crud->setModel($staff_model_new);		

		if($g=$crud->grid){
			$g->addQuickSearch(array('name','branch'));
			$g->addPaginator(10);
			$g->addColumn('button','deactive');
			$g->add('misc/Export');
			$g->addColumn('button','acl');

			if($_GET['acl']){
				$this->js()->univ()->frameURL('Staff ACL',$this->api->url('staffAcl',['staff_id'=>$_GET['acl']]))->execute();
			}
		}
	}

}
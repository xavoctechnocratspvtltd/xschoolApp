<?php

class page_master_session_main extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');
		$session_model=$this->add('Model_Session');
		
		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode

			$prev_session  = $crud->add('Model_Session')->setOrder('id','desc')->tryLoadAny();
			
			// Do your stuff by getting $form data
			$session_model_new = $crud->add('Model_Session');
			
			// CreatNew Function call
			// 

			try{

				$crud->api->db->beginTransaction();
				$session_model_new->createNew($form['name'],$form['start_date'],$form['end_date'],$form->getAllFields(),$form);
				$session_model_new->markCurrent();

				// DEFAULT SESSION BEAHVIOURS
				// 1. Add feeses to calsses as per prev session as base			
				foreach ($c=$crud->add('Model_Class') as $junk_class) {
					foreach ($feeses = $c->feeses($prev_session) as $junk_fees) {
							$c->addFees($feeses);
						}	
				}
			}catch(Exception $e){
				$crud->api->db->rollback();
				throw $e;
				
			}

			return true; // Always required
		});		
		

		$crud->setModel($session_model);



		if($g=$crud->grid){
			if($_GET['mark_current']){
				$session=$this->add('Model_Session');
				$session->load($_GET['mark_current']);
				$session->markCurrent();

				$g->js(null,$g->js()->reload())->_selector('.welcome-block')->trigger('reload')->execute();
			}

			$g->addMethod('format_currentsession',function($g,$f){
				if($g->model->id == $g->api->currentSession->id)
					$g->current_row_html[$f]='Yes';
				else
					$g->current_row_html[$f]='';

			});
			$g->addColumn('currentsession','current');

			$g->addColumn('button','mark_current');

			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));
		}
		
	}
}
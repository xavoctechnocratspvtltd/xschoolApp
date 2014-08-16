<?php

class page_master_exam_main extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');
		$exam=$this->add('Model_Exam');



		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$exam_model = $crud->add('Model_Exam');
			// CreatNew Function call
			// 
			try{
				$crud->api->db->beginTransaction();
				$exam_model->createNew($form['name'],$form->getAllFields(),$form);
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
				
			}
			return true; // Always required
		});		

		$crud->setModel($exam);

		
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}

		
	}
}
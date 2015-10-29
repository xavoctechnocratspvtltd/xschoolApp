<?php

class page_library_item extends Page {
	function init(){
		parent::init();
	
		try{
			$this->api->db->beginTransaction();
			
			$crud=$this->add('xCRUD');
			$item=$this->add('Model_Library_Item');	

		// $crud->addHook('myupdate',function($crud,$form){
		// 	if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
		// 	// Do your stuff by getting $form data
		// 	$item_model = $crud->add('Model_Library_Item');
		// 	// CreatNew Function call
		// 	$title=$crud->add('Model_Library_Title');
		// 	$title->load($form['title_id']);
		// 	$item_model->createNew($title,$form->getAllFields(),$form);
		// 	return true; // Always required
		// });	
			$crud->setModel($item);
			$this->api->db->commit();
		}catch(Exception $e){
			$this->api->db->rollBack();
			throw $e;
			
		}	
	
		if($g=$crud->grid){
			$g->addPaginator(10);
			$g->addQuickSearch(array('name'));

		}

	}

	function filterByBranch($branch){
		$this->addCondition('branch_id',$branch->id);
		return $this;
	}
}
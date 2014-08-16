<?php

class page_stock_item extends Page {
	function init(){
		parent::init();
	
		$crud=$this->add('xCRUD');

		$item=$this->add('Model_Stock_Item');

		$crud->addHook('myupdate',function($crud,$form){
			if($crud->isEditing('edit')) return false; // Always required to bypass the bellow code in editing crud mode
			
			// Do your stuff by getting $form data
			$item_model = $crud->add('Model_Stock_Item');
			// CreatNew Function call
			// 
			try{
				$crud->api->db->beginTransaction();
				$item_model->createNew($form['name'],$form->getAllFields(),$form);
			}catch(Exception $e){
				$crud->api->db->rollBack();
				throw $e;
			}
				
			return true; // Always required
		});
		
		$crud->setModel($item);		
	
		if($g=$crud->grid){

			$g->addMethod('format_currentstock',function($g,$f){
				$g->current_row[$f]=$g->model['total_inward']-$g->model['total_consume'];
			});
			
			$g->addColumn('currentstock','current_stock');
			$g->addPaginator(10);
			// $g->add('Order')->move('edit','after','qty')->now();
		}


	}
}
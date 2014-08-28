<?php

class page_stock_current extends Page {
	function init(){
		parent::init();

		$grid=$this->add('Grid');
		$item_model=$this->add('Model_Stock_Item');
		
		$grid->addMethod('format_qty',function($g,$f){
			$g->current_row[$f]=$g->model->getQty();
		});

		$grid->addMethod('format_inward',function($g,$f){

			$inward_tra = $g->add('Model_Stock_Transaction');
			$inward_tra->addCondition('item_id',$g->model->id);
			$inward_tra->addCondition('created_at','<',$g->api->nextDate($g->api->today));
			$inward_tra->addCondition('type','Inward');
			$inward_tra_qty = ($inward_tra->sum('qty')->getOne())?:0;
			$g->current_row[$f]=$inward_tra_qty;
		});

		$grid->addMethod('format_consume',function($g,$f){

			$consume_tra = $g->add('Model_Stock_Transaction');
			$consume_tra->addCondition('item_id',$g->model->id);
			$consume_tra->addCondition('created_at','<',$g->api->nextDate($g->api->today));
			$consume_tra->addCondition('type','Consume');
			$consume_tra_qty = ($consume_tra->sum('qty')->getOne())?:0;
			$g->current_row[$f]=$consume_tra_qty;
		});


		$grid->addMethod('format_issue',function($g,$f){

			$issue_tra = $g->add('Model_Stock_Transaction');
			$issue_tra->addCondition('item_id',$g->model->id);
			$issue_tra->addCondition('created_at','<',$g->api->nextDate($g->api->today));
			$issue_tra->addCondition('type','Issue');
			$issue_tra_qty = ($issue_tra->sum('qty')->getOne())?:0;
			$g->current_row[$f]=$issue_tra_qty;
		});

		$grid->addMethod('format_submit',function($g,$f){

			$submit_tra = $g->add('Model_Stock_Transaction');
			$submit_tra->addCondition('item_id',$g->model->id);
			$submit_tra->addCondition('created_at','<',$g->api->nextDate($g->api->today));
			$submit_tra->addCondition('type','Submit');
			$submit_tra_qty = ($submit_tra->sum('qty')->getOne())?:0;
			$g->current_row[$f]=$submit_tra_qty;
		});

		$grid->setModel($item_model,array('name','inward'));
		$grid->addColumn('qty','cuurent_stock');
		$grid->addColumn('inward','inward');
		$grid->addColumn('consume','consume');
		$grid->addColumn('issue','issue');
		$grid->addColumn('submit','submit');
		$grid->add('misc/Export');
	}
}
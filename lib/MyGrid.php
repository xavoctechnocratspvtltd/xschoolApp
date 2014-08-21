<?php

class MyGrid extends Grid_Advanced{
	
	public $my_totals=array();
	public $total_fields=array();
	public $total_text_in_field=null;

	function init(){
		parent::init();
		$this->addHook('formatRow',array($this,'incomeExpense'));
		$this->addHook('formatRow',array($this,'doMyTotals'));
	}

	function addMyTotals($array,$total_text_in_field){
		$this->total_fields = $array;
		$this->total_text_in_field = $total_text_in_field;
	}

	function doMyTotals(){
		foreach ($this->total_fields as $column) {
			if($column=='income' or $column='expense') continue;
			$this->my_totals[$column] = $this->my_totals[$column] + $this->current_row[$column];
		}
	}

	function incomeExpense(){
		$this->current_row['income'] = $this->current_row['expense'] = '';

			if(isset($this->model['transaction_type']) and $this->model['transaction_type']=='Income'){
				$this->current_row['income'] = $this->model['amount'];
				$this->my_totals['income'] = $this->my_totals['income'] + $this->current_row['income'];
			}else{
				$this->current_row['expense'] = $this->model['amount'];
				$this->my_totals['expense'] = $this->my_totals['expense'] +  $this->current_row['expense'];
			}
	}

	function renderRows(){
		parent::renderRows();

		$data=array();
		foreach ($this->columns as $column => $values) {
			$data[$column]='';
		}

		foreach ($this->total_fields as $c) {
			$data[$c] = $this->my_totals[$c];
		}

		$data[$this->total_text_in_field] = 'Total';

		$this->insertBefore($data);

		$data[$this->total_text_in_field] = 'Balance';
		$total=$this->my_totals['income'] - $this->my_totals['expense'];
		if($total<0){

			$data['income'] =$total ;
			$data['expense'] = '';
		}
		else{
			$data['income'] ='' ;
			$data['expense'] = $total;

		}

		$this->insertBefore($data);
	}

	function insertBefore($data){
		$saved_current_id = $this->current_id;
		$saved_current_row = $this->current_row;
		$saved_current_row_html = $this->current_row_html;
		foreach ($data as $key => $value) {
			$this->current_row_html[$key]=$value;
		}
		$this->template->appendHTML($this->container_tag,$this->rowRender($this->row_t));
		
		$this->current_id= $saved_current_id;
		$this->current_row = $saved_current_row;
		$this->current_row_html=$saved_current_row_html;
	}
}
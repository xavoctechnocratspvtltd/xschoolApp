<?php

class View_Student_PrintReceipt extends View {
	
	function setModel($receipt){

		parent::setModel($receipt);

		$this->template->set('school_name',$this->api->getConfig('school/name'));
		$this->template->set('sub1',$this->api->getConfig('school/sub1'));
		$this->template->set('sub2',$this->api->getConfig('school/sub2'));
		$this->template->set('receipt_no',$receipt['name']);
		$this->template->set('student_name',$receipt['student']);
		$this->template->set('transport_code',$receipt->ref('student_id')->ref('vehicle_id')->get('name'));
		$this->template->set('class',$receipt->ref('student_id')->ref('class_id')->get('full_name'));
		$this->template->set('scholar_no',$receipt->ref('student_id')->ref('scholar_id')->get('scholar_no'));
		
		$months = $receipt->satisfiedMonths();
		$str="";
		foreach ($months as $month => $due) {
			$str .= $month;
			if($due > 0)
				$str .= ' ('.$due.'/- Due) ';
			$str .= ", ";
		}
		$this->template->set('month',$str);
		$this->template->set('receipt_date',date('d-M-Y',strtotime($receipt['created_at'])));
	}

	function defaultTemplate(){
		return array('view/receiptprint');
	}
}
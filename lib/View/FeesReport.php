<?php
class View_FeesReport extends View {
	public $from_date=null;
	public $to_date=null;

	function init(){
		parent::init();

		if(!$this->from_date)
			$this->from_date = date('Y-m-01',strtotime($this->api->today));
		if(!$this->to_date)
			$this->to_date = date('Y-m-t',strtotime($this->api->today));

		$fees_transaction  = $this->add('Model_FeesTransaction');
		$fees_transaction->addCondition('submitted_on','>=',$this->from_date);
		$fees_transaction->addCondition('submitted_on','<',$this->api->nextDate($this->to_date));
		$student_applied_fees_join = $fees_transaction->join('student_fees_applied','student_applied_fees_id');
		$student_applied_fees_join->addField('fees_id');
		// $fees_join = $student_applied_fees_join->join('fees','fees_id');

		$fees_transaction->_dsql()->del('fields')
			->field('submitted_on')
			->field($student_applied_fees_join->table_alias.'.fees_id')
			->field('by_consession')
			->field('SUM(fees_transactions.amount) as total_amount');

		 $fees_transaction->addCondition('by_consession',false);

		$fees_transaction->_dsql()->group('submitted_on, _s.fees_id, fees_transactions.by_consession');
		
		// echo "<pre>";
		// print_r($fees_transaction->_dsql()->get());
		// echo "</pre>";

		$result_array = array();
		$grid=$this->add('Grid');
		
		$columns_added=array();
		$consession_stored_4_date=array();

		$grid->addColumn('text','date');
		$fees = $this->add('Model_Fees');
		
		foreach ($fees_transaction->_dsql()->get() as $data) {
			$fees->unload();
			$fees->load($data['fees_id']);
                        
            if(!isset($result_array[$data['submitted_on']])) $result_array[$data['submitted_on']] = array();
                        
			$result_array[$data['submitted_on']] += array(
					'date'=>$data['submitted_on'],
					$fees['name'] => $data['total_amount']
				);

			if(!in_array($fees['name'], $columns_added)){
				$grid->addColumn('text',$fees['name']);
				$columns_added[] = $fees['name'];
			}

			// consession time
			if(!in_array($data['submitted_on'], $consession_stored_4_date)){
				
				$result_array[$data['submitted_on']] += array(
					'consession'=> $this->add('Model_FeesTransaction')
									->sum('amount')
									->where('submitted_on',$data['submitted_on'])
									->where('by_consession',1)
									->getOne()
					);
				$consession_stored_4_date[] = $data['submitted_on'];
			}

		}
		$grid->addColumn('text','consession');
		$grid->setSource($result_array);
		
		$js=array(
			$this->js()->_selector('#header')->toggle(),
			$this->js()->_selector('#footer')->toggle(),
			$this->js()->_selector('ul.ui-tabs-nav')->toggle(),
			$this->js()->_selector('.atk-form')->toggle(),
			);

		$grid->js('click',$js);
		// echo "<pre>";
		// print_r($result_array);
		// echo "</pre>";

	}
}
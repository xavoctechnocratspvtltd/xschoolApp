<?php


class Model_Student extends Model_Table{
public $table="students";
	function init(){
		parent::init();

		$this->hasOne('Session','session_id');
		$this->hasOne('Scholar','scholar_id');
		$this->hasOne('Class','class_id','full_name');
		$this->hasOne('StudentType','studenttype_id');
        $this->hasOne('Vehicle','vehicle_id')->defaultValue(0);
		$this->addField('roll_no');
		$this->addField('ishostler')->type('boolean')->defaultValue(false)->caption("Is Hostler")->system(true);
        $this->addField('isScholared')->type('boolean')->system(true);
        $this->addField('given_consession')->type('money')->system(true);

        $this->addExpression('name')->set(function($m,$q){
        	return $m->refSQL('scholar_id')->fieldQuery('name');
        });

        $this->addExpression('scholar_no')->set(function($m,$q){
        	return $m->refSQL('scholar_id')->fieldQuery('scholar_no');
        });

        $this->addExpression('total_applied_fees_sum')->set(function($m,$q){
        	return $m->refSQL('StudentAppliedFees')->sum('amount');
        });

        $this->addExpression('total_paid_fees_sum')->set(function($m,$q){
        	return $m->refSQL('FeesTransaction')->sum('amount');
        });

        $this->addExpression('applied_fees_sum_till_date')->set(function($m,$q){
        	return $m->refSQL('StudentAppliedFees')->addCondition('due_on','<=',$m->api->today)->sum('amount');
        });

        $this->addExpression('paid_fees_sum_till_date')->set(function($m,$q){
        	return $m->refSQL('FeesTransaction')->addCondition('submitted_on','<=',$m->api->today)->sum('amount');
        });
        
		$this->hasMany('Student_Attendance','student_id');
        $this->hasMany('Marks','student_id');
        $this->hasMany('StudentAppliedFees','student_id');
        $this->hasMany('FeesTransaction','student_id');
        $this->hasMany('FeesReceipt','student_id');

        $this->addHook('beforeDelete',$this);

        $this->setOrder('name');

	    // $this->add('dynamic_model/Controller_AutoCreator');
	}


	function createNew($scholar,$class, $studenttype, $session=null){
		if(!$session) $session=$this->api->currentSession;

		if($this->loaded())
			throw $this->exception('Student must be created from empty model');
		$this['scholar_id'] = $scholar->id;
		$this['class_id'] = $class->id;
		$this['session_id'] = $session->id;
		$this['studenttype_id'] = ($studenttype instanceof Model_StudentType) ? $studenttype->id : $studenttype;
		$this->save();
		return $this;
	}

	function shiftToClass($class){

		if($this->isInClass($class, $this->ref('scholar_id')))
			throw $this->exception('Student is already in the same class', 'ValidityCheck')->setField('class');
		
		$this['class_id']=$class->id;
		$this->save();
		return $this;
	}

	function isInClass($class,$scholar,$session=null){
		if(!$session) $session = $this->api->currentSession;

		$check = $this->add('Model_Student');

		$check->addCondition('class_id',$class->id);
		$check->addCondition('scholar_id',$scholar->id);
		$check->addCondition('session_id',$session->id);
		$check->tryLoadAny();
		if($check->loaded())
			return $check;
		else
			return false;
	}

	function deleteForced(){
		$stf=$this->ref('StudentAppliedFees');
		$sta=$this->ref('Student_Attendance');
		// $stm=$this->ref('Marks');

		foreach ($stf as $junk) {
			$stf->deleteForced();
		}

		foreach ($sta as $junk) {
			$stf->deleteForced();
		}

		// foreach ($stm as $junk) {
		// 	$stm->deleteForced();
		// }

		// throw $this->exception('Marks remove for student or any other leftover ????');

		$this->delete();
		// check any entry regarding this student
		
	}

	function beforeDelete(){
		// throw exception if any
		if($this->ref('StudentAppliedFees')->count()->getOne()>0)
			throw $this->exception("You can Not Delete, It Contain Fees records");
			
		if($this->ref('Student_Attendance')->count()->getOne()>0)
			throw $this->exception("You can Not Delete, It Contain Attendance records");
		
		// if($this->ref('Marks')->count()->getOne()>0)
		// 	throw $this->exception("You can Not Delete, It Contain Marks records");


	}

	function inClass($session=null){

		if($session==null) $session=$this->api->currentSession;
		return $this->isInClass($session);

	}

	function type(){
		return $this->ref('studenttype_id');
	}

	function vehicle(){
		return $this->ref('vehicle_id');
	}

	function assignVehicle($vehicle){

		if(!$vehicle instanceof Model_Vehicle)
			throw $this->exception("assignVehicle Must be passed a loaded Object of Model_Vehicle");
			

		if(!$this->loaded())
			throw $this->exception("Specify the student ID, Model Student Must be loaded before traversing");
		$this['vehicle_id']=$vehicle->id;
		$this->save();
		
		return $this;			
	}

	function removeVehicle(){

		if(!$this->loaded())
			throw $this->exception("Specify the student ID, Model Student Must be loaded before traversing");
		$this['vehicle_id']=0;
		$this->save();
		
		return $this;			
	}

	function applyFees($fees){
		return $this->addFees($fees);
	}

	function addFees($fees){
		if($this->hasFeesApplied($fees))
			throw $this->exception('Student is already applied with the fees');
		$fees_for_this_student = $this->add('Model_StudentAppliedFees');
		$fees_for_this_student->createNew($this,$fees);
		return $this;
	}

	function isExist($class,$scholar,$session){
		if($this->loaded())
			throw $this->exception("You cannot use loaded Model on isExist function in Model_Student");
			
		if($session==null) $session=$this->api->currentSession;

		$this->addCondition('class_id',$class->id);
		$this->addCondition('scholar_id',$scholar->id);
		$this->addCondition('session_id',$session->id);
		$this->tryLoadAny();

		if($this->loaded())
			return $this;
		else
			return false;

	}

	function removeFees($fees){
		if(!$this->hasFeesApplied($fees))
			return;
			// throw $this->exception('Fees is not applied to student, cannot remove');

		if($this->hasFeesApplied($fees)){
			$fees_for_this_student = $this->appliedFees($fees);
			foreach ($fees_for_this_student as $junk) {
				$fees_for_this_student->delete();
			}
		}

	}


	function hasFeesApplied($fees){
		$student_fees_applied = $this->add('Model_StudentAppliedFees');
		return $student_fees_applied->hasAssociation($this,$fees);
	}

	function appliedFees($fees=null){
		$fees_for_this_student = $this->add('Model_StudentAppliedFees');
		if($fees){
			$fees_for_this_student->associations($this,$fees);
		}
		else{
			$fees_for_this_student->addCondition('student_id',$this->id);
		}
		return $fees_for_this_student;	
	}

	function submitFees($amount,$mode,$narration="",$late_fees = 0 ){
		
		$narration .= " :: Fees Received From ".$this['name']." ( ".$this['scholar_no']." ) ";
		$receipt=$this->add('Model_FeesReceipt');
		$receipt->createNew($this,$amount,$mode,$narration);
		
		$transaction=$this->add('Model_PaymentTransaction');
		$transaction->createNew($amount,"Income",$mode,$narration,$receipt->id);
		
		return $receipt;
	}

	function consessionInFees($amount){


		if($amount > $this->getDueFeesAmount() or $amount < 0)
			throw $this->exception('Cannot give consession more than  remainig amount or negative consession','ValidityCheck')->setField('consession');

		$this_student_applied_fees = $this->appliedFees();
		$this_student_applied_fees->setOrder('due_on desc, id desc');

		$remaining_amount_to_consession = $amount;

		foreach ($this_student_applied_fees as $junk) {
			// echo $this_student_applied_fees['due_on'].' ' . $this_student_applied_fees->ref('fees_id')->get('name') . ' ';
			if($remaining_amount_to_consession >= $this_student_applied_fees['amount']){
				$remaining_amount_to_consession = $remaining_amount_to_consession - $this_student_applied_fees['amount'];
				$this_student_applied_fees['amount'] = 0;
				// echo ' done 0 <br/>';
				$this_student_applied_fees->save();
			}else{
				$this_student_applied_fees['amount'] = $this_student_applied_fees['amount'] - $remaining_amount_to_consession;
				$remaining_amount_to_consession=0;
				// echo ' done '.$this_student_applied_fees['amount'].'  <br/>';
				$this_student_applied_fees->save();
			}
			if($remaining_amount_to_consession == 0 ) break; // no need to check more fees.. all amount is adjusted
		}

		$this['given_consession'] = $this['given_consession'] + $amount;
		$this->save();

	}

	function feesReceipts(){
		if(!$this->loaded())
			throw $this->exception(' Student Must be loaded to get fess receipt');
		return $this->ref('FeesReceipt');
	}

	function getFeesCard(){

		$array=array();

		$fees_for_this_student = $this->add('Model_StudentAppliedFees');
		$fees_for_this_student->join('fees','fees_id')->addField('distribution');

		$fees_for_this_student->addCondition('student_id',$this->id);

		foreach ($fees_for_this_student as $junk) {
			$done=false;
			foreach ($array as &$existing_array) {
				// Add to exiting month
				if($existing_array['month'] == date('M Y',strtotime($fees_for_this_student['due_on']))){
					$existing_array['total_fees'] += $fees_for_this_student['amount'];
					$existing_array['paid_fees'] += $fees_for_this_student->paidAmount();
					$done=true;
				}
			}

			if($done) continue;

			// This Month is first time in database
			$array[] 
				= array(
					'id'=>json_encode(array('student_id'=>$this->id,'date'=>$fees_for_this_student['due_on'])),
					'month'=>date('M Y',strtotime($fees_for_this_student['due_on'])),
					'total_fees'=>$fees_for_this_student['amount']?:0,
					'paid_fees' => $fees_for_this_student->paidAmount()?:0,
					'fees_status'=>123
				);
		}
		

		return $array;

	}

	function getDueFeesAmount($month=null){
		$due_fees = 0;

		$fees_for_this_student = $this->appliedFees();
		if($month){
			if(! in_array($month, array(1,2,3,4,5,6,7,8,9,10,11,12))) throw $this->exception('$month must be non zero leading int month');
			$fees_for_this_student->addExpression('due_on_month_year')->set('EXTRACT(YEAR_MONTH FROM due_on)');
			$fees_for_this_student->addCondition('due_on_month_year',WHAT_I_M);
		}

		foreach ($fees_for_this_student as $fees_for_this_student_array) {
			$due_fees += $fees_for_this_student['amount']-$fees_for_this_student->paidAmount() ;
		}

		return $due_fees;
	}

	function getLateFees(){
		$late_fee_per_day = $this->api->getConfig('school/late_fee_per_day');

		return $late_fee_per_day;
	}

	function changeClass($to_class, $change_feeses_also =false){
		if(!$this->loaded()) throw $this->exception('Student must be loaded to change class');

		if(!$change_feeses_also){ // Simple change class ID and keep everything else same
			$this['class_id'] = $to_class->id;
			$this->save();
			return;
		}

		$current_class = $this->ref('class_id');
		$current_class->removeStudent($this,true);
		$to_class->addStudent($this, $this['studenttype_id']);

		return $this;
	}

	function countByCast(){

		$scholar_join = $this->join('scholars','scholar_id');
		$class_join = $this->join('classes','class_id');

		$result = $this->_dsql()->del('fields')
				->field($class_join->table_alias.'.name')
				->field($class_join->table_alias.'.section')
				->field($scholar_join->table_alias.'.cast')
				->field('count(*)')
				->group("class_id, ".$scholar_join->table_alias.".cast")
				->get();


		$new_array = array();
		$casts = array();

		foreach ($result as $data) {
			if(isset($new_array[$data['name'].$data['section']]))
				$new_array[$data['name'].$data['section']] += array('class'=>$data['name']. '-'. $data['section'], $data['cast']=>$data['count(*)']);
			else
				$new_array[$data['name'].$data['section']] = array('class'=>$data['name']. '-'. $data['section'], $data['cast']=>$data['count(*)']);

			if(!in_array($data['cast'], $casts))
				$casts[] = $data['cast'];
		}


		return array('casts'=>$casts,'count'=>$new_array);
		
	}

	function countByCategory(){
		$scholar_join = $this->join('scholars','scholar_id');
		$class_join = $this->join('classes','class_id');

		$result = $this->_dsql()->del('fields')
				->field($class_join->table_alias.'.name')
				->field($class_join->table_alias.'.section')
				->field($scholar_join->table_alias.'.category')
				->field('count(*)')
				->group("class_id, ".$scholar_join->table_alias.".category")
				->get();


		$new_array = array();
		$casts = array();

		foreach ($result as $data) {
			if(isset($new_array[$data['name'].$data['section']]))
				$new_array[$data['name'].$data['section']] += array('class'=>$data['name']. '-'. $data['section'], $data['category']=>$data['count(*)']);
			else
				$new_array[$data['name'].$data['section']] = array('class'=>$data['name']. '-'. $data['section'], $data['category']=>$data['count(*)']);

			if(!in_array($data['category'], $casts))
				$casts[] = $data['category'];
		}


		return array('category'=>$casts,'count'=>$new_array);
	}

}
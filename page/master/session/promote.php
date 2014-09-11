<?php

class page_master_session_promote extends Page{
	function init(){
		parent::init();
		$form=$this->add('Form');
		$class=$this->add('Model_Class');
		// throw new Exception($class->count()->getOne(), 1);
		
		$i=1;
		// foreach ($class as $junk) {
				# code...
			$from_class_field=$form->addField('autocomplete/Basic','from_class_'.$i);
			$from_class_model=$this->add('Model_Class');
			$from_class_model->title_field='full_name';
			$from_class_field->setModel($from_class_model);
			$to_class_field=$form->addField('autocomplete/Basic','to_class_'.$i);
			$to_class_model=$this->add('Model_Class');
			$to_class_model->title_field='full_name';
			$to_class_field->setModel($to_class_model);
			$student_type_field=$form->addField('autocomplete/Basic','student_type');
			$student_type_field->setModel('StudentType');

			if($form->isSubmitted()){

				$class=$this->add('Model_Class')->tryLoad($form['from_class_'.$i]);
				$to_class=$this->add('Model_Class')->tryLoad($form['to_class_'.$i]);
				
				$students=$class->ref('Student');//->addCondition('is_left',false);
				foreach ($students as $junk) {
					// $s=$students->allReadyInSession($class);
					$st=$this->add('Model_Student');
					$scholar=$this->add('Model_Scholar');
					$scholar->load($students['scholar_id']);
					// throw new Exception($s['name'], 1);
					if($st->allReadyInSession($scholar,$to_class))
						throw new Exception("This Class students are already promoted in This session", 1);
						$to_class->addStudent($scholar, $form['student_type']);						
						// $entry_in_student_model['session_id']=$this->api->currentSession->id;
						// $entry_in_student_model['scholar_id']=$students['scholar_id'];
						// $entry_in_student_model['class_id']=$form['to_class_'.$i];
						// $entry_in_student_model['studenttype_id']=$form['student_type_'.$i];
						// $entry_in_student_model['vehicle_id']=$students['vehicle_id'];
						// $entry_in_student_model['roll_no']=0;
						// $entry_in_student_model->save();
						// foreach ($class->feeses($prev_session) as $junk_fees) {
						// 	$entry_in_student_model->addFees($junk_fees);
						// }
						

				}
				
				}
			// $i++;
		// }

		$form->addSubmit('Promote');
	}
}
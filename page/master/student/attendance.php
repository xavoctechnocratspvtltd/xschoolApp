<?php
class page_master_student_attendance extends Page {
	function init(){
		parent::init();

		$class=$this->api->currentBranch->classes();
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class);
		$class->title_field="full_name";

		$month_field=$form->addField('dropdown','month');
		$month_field->setValueList(array('1'=>'January',
            							'2'=>'February',
            							'3'=>'March',
            							'4'=>'April',
            							'5'=>'May',
            							'6'=>'Jun',
            							'7'=>'July',
            							'8'=>'Augst',
            							'9'=>'September',
            							'10'=>'Octomber',
            							'11'=>'November',
            							'12'=>'December'
            							));
		$att=$form->addField('line','att','Total Monthly Attendance');
	 	$form->addField('checkbox','change_total_attendance');
		$form->addSubmit('Allot');
		$grid=$this->add('Grid');

		if($form->isSubmitted()){
			$class_model=$this->add('Model_Class')->load($form['class']);
			$attendance=$this->add('Model_Student_Attendance');
			try{
				$total_students_in_attendance_table=
				$attendance->students($class_model,$form['month'],null,true);

				$student=$this->add('Model_Student');
				$total_student_in_class_table=$student->classStudents($class_model,null,true);
				if($total_students_in_attendance_table!=$total_student_in_class_table){
					foreach ($st=$class_model->allStudents() as $junk) {
						$att=$this->add('Model_Student_Attendance');
						if(!$att->isExist($class_model,$st,$form['month']))
								$att->createNew($class_model,$st,$form['month'],$form['att']);

					}
				}else{
					if($form['att'] and $form['change_total_attendance']==false)
	                        $form->displayError('att','Please Check the CheckBox for Fill Attendance');
	                    if($form['att'] and $form['att']!=$attendance['total_attendance'] and $form['change_total_attendance'] == true){
	                        $attendance->unload();
	                        $attendance->_dsql()->set('total_attendance',$form['att'])->update();
	                    }
				}

			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
				
			$grid->js()->reload()->execute();
		}

		$grid->setModel('Student_Attendance');

	}
}
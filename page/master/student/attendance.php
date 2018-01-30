	<?php
class page_master_student_attendance extends Page {
	function init(){
		parent::init();
		$this->add('Controller_ACL');
		$class=$this->api->currentBranch->classes();
		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_field->setModel($class);
		$class->title_field="full_name";

		$month_field=$form->addField('dropdown','month');
		$month_field->setValueList(array('1'=>'January',
            							'2'=>'February ',
            							'3'=>'March',
            							'4'=>'April',
            							'5'=>'May',
            							'6'=>'June',
            							'7'=>'July',
            							'8'=>'August',
            							'9'=>'September',
            							'10'=>'October',
            							'11'=>'November',
            							'12'=>'December'
            							));
		$att=$form->addField('line','att','Total Monthly Attendance');
	 	$form->addField('checkbox','change_total_attendance');
		$form->addSubmit('Allot');
		$grid=$this->add('CRUD',['allow_add'=>false]);
		$student_attendance=$this->add('Model_Student_Attendance');
		$student_attendance->addCondition('session_id',$this->api->currentSession->id);
		if($_GET['filter']){
			$this->api->stickyGET('filter');
			if($_GET['class']){
				$this->api->stickyGET('class');
				$student_attendance->addCondition('class_id',$_GET['class']);
			}
			if($_GET['month']){
				$this->api->stickyGET('month');
				$student_attendance->addCondition('month',$_GET['month']);
			}
		}else{
			$student_attendance->addCondition('id',0);
		}

		if($form->isSubmitted()){
			$class_model=$this->add('Model_Class')->load($form['class']);
			
			$class_model->title_field='full_name';
			$attendance=$this->add('Model_Student_Attendance');

				$total_students_in_attendance_table=
				$attendance->students($class_model,$form['month'],null,true);

				$student=$this->add('Model_Student');
				$student->addCondition('is_left',false);
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

			
			$grid->js()->reload(array('class'=>$form['class'],'month'=>$form['month'],'filter'=>1))->execute();
		}


		$grid->setModel($student_attendance,array('student','class','session','roll_no','total_attendance','present'));
	}
}
<?php

class View_Promote extends View{

	function init(){
		parent::init();
		
		$this->api->stickyGET('from_class');
		$class=$this->add('Model_Class');
		$class->load($_GET['class']);
		$students=$class->students()->setOrder('id');

		$form=$this->add('Form');
		$student_type=$this->add('Model_StudentType');
		$i=1;
		foreach ($students as $junk) {
			$student_field=$form->addField('line','student_'.$i);
			$student->load($students['id']);
			$student_id_field=$form->addField('line','student_id'.$i);
			// $student_field->template->addSeprator('class','span6');
			$student_field->set($student['name']);		
			$student_id_field->set($student['id']);		
			$student_type_field=$form->addField('dropdown','student_type_'.$i);
			$student_type_field->setModel($student_type);		
			$student_type_field->set($students['studenttype_id']);
			$i++;
		}		

		$to_class_field=$form->addField('autocomplete/Basic','to_class');
		$to_class_field->setModel('Class');

		$form->addSubmit('Promote');

		if($form->isSubmitted()){

				$to_class=$this->add('Model_Class')->tryLoad($form['to_class']);
				
				$students=$class->students()->setOrder('id');
				$i=1;
				foreach ($students as $junk) {
					// $s=$students->allReadyInSession($class);
					$st=$this->add('Model_Student');
					$scholar=$this->add('Model_Scholar');
					$scholar->load($students['scholar_id']);
					// throw new Exception($s['name'], 1);
					
					if($st->allReadyInSession($scholar,$to_class))
						throw new Exception("This Class students are already promoted in This session", 1);
					
					$to_class->addStudent($scholar, $form['student_type_'.$i]);						
						
					$i++;
				}
				
			}
	}
}
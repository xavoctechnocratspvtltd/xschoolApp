<?php
class page_exam_marksassign extends Page {
	function page_index(){
		// parent::init();

		

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class');
		$class_model=$this->api->currentBranch->classes();
		$class_model->title_field='full_name';
		$class_field->setModel($class_model);
		if($_REQUEST[$class_field->name] or $this->api->recall('markasign',false)){
			$this->api->memorize('markasign',$_REQUEST[$class_field->name]);
			$class_model->load($_REQUEST[$class_field->name]);
		}else{
			$class_model->tryLoadAny();
		}
		$subject_model=$class_model->allSubjects();

		$exam_model=$class_model->allExams();

		$subject_field=$form->addField('dropdown','subject');
		$subject_field->setModel($subject_model);
		$exam_field=$form->addField('dropdown','exam');

		

		$exam_field->setModel($exam_model);

		$form->addSubmit('GET LIST');

		$class_field->js('change',array(
				$form->js()->atk4_form('reloadField','subject',array($this->api->url(),$class_field->name=>$class_field->js()->val()),$subject_field->js()->trigger('change')->_enclose()),
			)
		);

		$class_field->js('change',$form->js()->atk4_form('reloadField','exam',array($this->api->url(),$class_field->name=>$class_field->js()->val())));

		// $grid=$col1->add('Grid');
		// $student_marks=$this->add('Model_Student_Marks');
		// $grid->setModel($student_marks,array('student','marks'));


		if($form->isSubmitted()){
			$marks=$this->add('Model_Student_Marks');
			$class=$this->add('Model_Class');
			$class->load($form['class']);

			$subject=$this->add('Model_Subject');
			$subject->load($form['subject']);

			$exam=$this->add('Model_Exam');
			$exam->load($form['exam']);

			$students=$class->students();
			foreach ($students as $key => $value) {
				if(!$marks->isAvailable($students,$subject,$exam,$class))
					$marks->createNew(0,$students,$subject,$exam,$class);
			}

			$form->js()->univ()->newWindow($this->api->url('marks',array('class'=>$class['full_name'],'exam'=>$exam['name'],'subject'=>$subject['name'])))->execute();
		}

	}
}
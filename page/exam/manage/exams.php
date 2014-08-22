<?php
class page_exam_manage_exams extends Page {
	public $class;

	function init(){
		parent::init();
		$this->api->stickyGET('classes_id');
		$this->class=$this->add('Model_Class');
		$this->class->load($_GET['classes_id']);

	}

	function page_index(){
		// parent::init();

		
		$exams=$this->class->allExams();
		$grid=$this->add('Grid');
		$grid->setModel($exams);

		$grid->addColumn('expander','subjects');


	}

	function page_subjects(){
		$btn=$this->add('Button')->set('Add Subject');
		$this->api->stickyGET('exams_id');
		$this->api->stickyGET('classes_id');
		$subjects=$this->class->allSubjects();
		// $subjects->addCondition('exam_id',$_GET['exam_id']);
		$form=$this->add('Form',null,null,array('form_horizontal'));

		$subject_field=$form->addField('dropdown','subject');
		$subject_field->setModel($subjects);

		$form->addField('line','max_marks');
		$form->addField('line','min_marks');
		$form->addSubmit('Add');

		$form->js(true)->hide();
		$btn->js('click',$form->js()->toggle());

		$assign_marks=$this->add('Model_SubjectInExamClass');
		$assign_marks->addCondition('exam_id',$_GET['exams_id']);
		$assign_marks->addCondition('class_id',$_GET['classes_id']);
		$grid=$this->add('Grid');

		if($_GET['remove']){
			$assign_marks->load($_GET['remove']);
			try{
				$this->api->db->beginTransaction();
				$assign_marks->remove();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
			}
				
			$grid->js(null,$grid->js()->reload())->univ()->successMessage('Removed Successfully')->execute();
		}
		$grid->setModel($assign_marks,array('subject','min_marks','max_marks'));
		$grid->addColumn('button','remove');
		if($form->isSubmitted()){
			
			if($form['max_marks']<$form['min_marks'])
				$form->displayError('max_marks','Max Marks Must be greater than Min Marks');
			$exam=$this->add('Model_Exam');
			$exam->load($_GET['exams_id']);
			$subject=$this->add('Model_Subject');
			$subject->load($form['subject']);

			if(!$assign_marks->isAvailable($subject,$exam,$this->class)){
				// try{

					// $this->api->db->beginTransaction();
					$assign_marks->createNew($subject,$exam,$this->class,$form->getAllFields());
					
				// }catch(Exception $e){
				// 	$this->api->db->rollBack();
				// 	throw $e;
				// }
					
				$form->js(null,$grid->js()->reload())->univ()->successMessage('Added Successfully')->execute();
				
			}else{
				$form->js()->univ()->errorMessage('Allready Associate')->execute();

			}

			
		}

	}
}
<?php

class page_reports_marks extends Page {
	public $total=0;
	function init(){
		parent::init();


		$class = $this->add('Model_Class')->load($_GET['class_id']);
		$term=null;
		if($_GET['term_id']){
			$term = $this->add('Model_Term')->load($_GET['term_id']);
		}

		$this->add('H3')->set('Exam Marks Reports For '.$class['full_name'])->setAttr('align','center');
		$this->js(true)->_selector('#header')->hide();

		$subject = $this->add('Model_SubjectInAClass');
		$subject->addCondition('class_id',$_GET['class_id']);
		$subject->addCondition('session_id',$this->api->currentSession->id);

		$exams = $this->add('Model_ExamInAClass');
		$exam_j = $exams->join('exams','exam_id');
		$exam_j->addField('term_id');		
		$exams->addCondition('term_id',$_GET['term_id']);
		$exams->addCondition('class_id',$_GET['class_id']);
		$exams->addCondition('session_id',$this->api->currentSession->id);
		
		$grid = $this->add('Grid');
		$grid->total=0;
		$grid->max_marks=0;
		$grid->grand_total=0;
		$grid->grand_max_marks=0;
		// $columns=array();
		
		$class_students = $class->students();
		// $class_students->setLimit(10);

		$class_students->addExpression('Attandance')->set(function($m,$q){
			 return $m->refSQL('Student_Attendance')->addCondition('session_id',$m->api->currentSession->id)->sum('present');
		});

		$subject_array=array(0);
		$exam_array=array(0);

		foreach ($subject as $sub) {
			// if(!$sub['name']) continue;
			foreach ($exams as $exam) {

				if(!in_array($sub['subject_id'],$subject_array)) $subject_array[] = $sub['subject_id'];
				if(!in_array($exam['exam_id'],$exam_array)) $exam_array[] = $exam['exam_id'];
				$grid->addMethod('format_'. $this->api->normalizeName($sub['subject'].$exam['exam']),function($g,$f)use($sub,$exam){
					$marks = $g->add('Model_Student_Marks');
					$marks->addCondition('class_id',$_GET['class_id']);
					$marks->addCondition('subject_id',$sub['subject_id']);
					// echo "exam :".$exam['name']."</br>";
					// $columns += array_values($exam);
					$marks->addCondition('exam_id',$exam['exam_id']);
					$marks->addCondition('student_id',$g->model->id);
					$marks->tryLoadAny();
					$g->current_row_html[$f]=$marks['marks'];
					$g->total +=$marks['marks'];
					$g->grand_total +=$marks['marks'];

					$marks_detail = $g->add('Model_SubjectInExamClass');
					$marks_detail->addCondition('class_id',$_GET['class_id']);
					$marks_detail->addCondition('subject_id',$sub['subject_id']);
					$marks_detail->addCondition('exam_id',$exam['exam_id']);
					$marks_detail->tryLoadAny();

					$g->max_marks += $marks_detail['max_marks'];
					$g->grand_max_marks += $marks_detail->sum('max_marks')->getOne()?:0;

				});

				$grid->addColumn($this->api->normalizeName($sub['subject'].$exam['exam']),$exam['exam']." - ".$sub['subject']);
			}

			$grid->addMethod('format_'.$this->api->normalizeName($sub['subject']).'_total',function($g,$f){
				$g->current_row_html[$f]=$g->total;
			});

			$grid->addMethod('format_'.$this->api->normalizeName($sub['subject']).'_grade',function($g,$f){

				$g->current_row_html[$f]=$g->api->currentSession->getGrade($g->total?:0, $g->max_marks?:1);
				
				$g->total=0;
				$g->max_marks=0;
			});

			$grid->addColumn($this->api->normalizeName($sub['subject']).'_total',$sub['subject']."_Total");
			$grid->addColumn($this->api->normalizeName($sub['subject']).'_grade',$sub['subject']."_grade");
			// $grid->total=0;
		}

		// print_r($subject_array);
		$class_students->addExpression('rank_total')->set(function($m,$q)use($subject_array,$exam_array){
				return $m->refSQL('Student_Marks')
				->addCondition('session_id',$m->api->currentSession->id)
				->addCondition('exam_id',$exam_array)
				->addCondition('subject_id',$subject_array)
				->sum('marks');
			});
			
			$class_students->setOrder('rank_total','desc');

		// grand total
		$grid->addMethod('format_grand_total',function($g,$f){
			$g->current_row[$f]=$g->grand_total;
			// $g->grand_total=0;
		});
		$grid->addColumn('grand_total','grand_total');

		//Grand max marks
		$grid->addMethod('format_grand_max_marks',function($g,$f){
			$g->current_row[$f]=$g->grand_max_marks;
		});
		$grid->addColumn('grand_max_marks','grand_max_marks');
		
		//Grand Grade
		$grid->addMethod('format_total_grade',function($g,$f){
				$g->current_row_html[$f]=$g->api->currentSession->getGrade($g->grand_total?:0, $g->grand_max_marks?:1);
				$g->grand_total=0;
				$g->grand_max_marks=0;
			});
		$grid->addColumn("total_grade","total_grade");

		//Adding Rank 
		$grid->rank=1;
		$grid->addMethod('format_rank',function($g,$f){
			$g->current_row[$f] = $g->rank++;
		});

		$grid->setModel($class_students);
		$grid->setFormatter('rank_total','rank');

		$grid->removeColumn('studenttype');
		$grid->removeColumn('vehicle');
		$grid->removeColumn('phone_no');
		$grid->removeColumn('scholar');
		$grid->removeColumn('scholar_no');
		$grid->removeColumn('session');

		$grid->addOrder()->move('roll_no','first')->now();
		$grid->addOrder()->move('class','after','roll_no')->now();
		$grid->addOrder()->move('name','after','class')->now();

		// foreach ($columns as $col) {
		// 	$grid->addColumn('text',$col);
		// 	// echo $col;	
		// }

		// $result = $class->getResult($term);


		// foreach ($result as $junk) {
		// 	$columns += array_keys($junk);
		// }



		// // array_multisort($columns);
		// // echo "<pre>";
		// // print_r($result);
		// // echo "</pre>";

		// $grid = $this->add('Grid',null,null,array('view/marksgrid'));

		// $grid->getElement('student_name')->add('Order')->move('first')->now();
	}
}
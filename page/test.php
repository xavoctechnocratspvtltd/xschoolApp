<?php

class page_test extends Page {

	function init(){
		parent::init();
		// $student = $this->add('Model_Class');
		// $student->load(32);
		// $term = $this->add('Model_Term')->load(1);
		// echo "<pre>";
		// print_r($student->getResult());
		// echo "</pre>";

		$st=$this->add('Model_Student');
		$st->load(1585);
		$stu=$st->appliedFees();
		// $stu->addCondition('')
		$grid=$this->add('Grid');
		$grid->setModel($stu->debug());
	}

	function page_consession(){
		/*
		UPDATE student_fees_applied
JOIN 
(SELECT
student_fees_applied.id,
student_fees_applied.student_id,
student_fees_applied.amount AS feeded_amount,
student_fees_applied.fees_id,
student_fees_applied.due_on,
 IF ( fees.distribution = 'No' , fees_amount_for_student_types.amount, fees_amount_for_student_types.amount / 8 )  AS actual_amount,
fees.distribution
FROM
student_fees_applied
INNER JOIN students ON student_fees_applied.student_id = students.id
INNER JOIN fees_amount_for_student_types ON students.studenttype_id = fees_amount_for_student_types.studenttype_id AND student_fees_applied.fees_id = fees_amount_for_student_types.fees_id
INNER JOIN fees ON fees_amount_for_student_types.fees_id = fees.id
HAVING 
feeded_amount <> actual_amount
and 
feeded_amount <> 2*actual_amount
) as temp on temp.id= student_fees_applied.id

SET
amount = temp.actual_amount
		 */
	}
}
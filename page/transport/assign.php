<?php

class page_transport_assign extends Page{
	function page_index() {
		// parent::init();

		$form=$this->add( 'Form', null, null, array( 'form_horizontal' ) );
		$class_field=$form->addField( 'dropdown', 'class' )->setEmptyText( 'Please Select' );
		$student_field=$form->addField( 'dropdown', 'student' )->setEmptyText( 'Please Select' );


		$classes = $this->api->currentBranch->classes();
		$classes->title_field='full_name';

		$class_field->setModel( $classes );

		$class_model = $this->add( 'Model_Class' );
		if ( $_REQUEST[$class_field->name] ) {
			$this->api->stickyGET( $class_field->name );
			$class_model->load( $_REQUEST[$class_field->name] );
		}else {
			$class_model=$classes->tryLoadAny();
		}

		$students=$class_model->students();

		$student_field->setModel( $students );


		$form->addSubmit( 'Get Details' );


		$class_field->js( 'change', $form->js()->atk4_form( 'reloadField', 'student', array( $this->api->url(), $class_field->name=>$class_field->js()->val() ) ) );

		$grid= $this->add( 'Grid' );
		$student=$this->add( 'Model_CurrentStudent' );
		$student->addCondition('is_left',false);

		if ( $_REQUEST['filter'] ) {
			$this->api->stickyGET('filter');
			$this->api->stickyGET($class_field->name);
			$this->api->stickyGET($student_field->name);

			if ( $_REQUEST[$class_field->name] )
				$student->addCondition( 'class_id', $_REQUEST[$class_field->name] );
			if ( $_REQUEST[$student_field->name] )
				$student->addCondition( 'id', $_REQUEST[$student_field->name] );
		}else {
			$student->addCondition( 'id', -1 );
		}



		$grid->setModel( $student );

		if($_GET['remove_vehicle']){
			$student->load($_GET['remove_vehicle']);
			try{
				$this->api->db->beginTransaction();
				$student->removeVehicle();
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
			$grid->js()->reload()->execute();
		}
		// $grid->add('Order')->move('last','vehicle')->now();
		$grid->addColumn('expander','assignvehicle');
		$grid->addColumn('button','remove_vehicle');
		$grid->addClass('assign');
		$grid->js('reload')->reload();



		if ( $form->isSubmitted() ) {
			$grid->js()->reload( array( 'filter'=>1, $class_field->name=>$form['class'], $student_field->name=>$form['student'] ) )->execute();
		}

	}

	function page_assignvehicle(){

		$this->api->stickyGET('students_id');

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$vehicle_field=$form->addField('dropdown','vehicle');
		$vehicle_field->setModel('Vehicle');
		$form->addSubmit('Assign');

		if($form->isSubmitted()){
			$vehicle=$this->add('Model_Vehicle');
			$vehicle->load($form['vehicle']);
			$student=$this->add('Model_Student');
			$student->load($_GET['students_id']);
			try{
				$this->api->db->beginTransaction();
				$student->assignVehicle($vehicle);
				$this->api->db->commit();
			}catch(Exception $e){
				$this->api->db->rollBack();
				throw $e;
				
			}
			$form->js(null,array($form->js()->univ()->closeExpander(),$form->js()->_selector('.assign')->trigger('reload')))->univ()->successMessage('Assigned Successfully')->execute();
		}


	}

}

<?php
class page_master_student_rollno extends Page{
	function init(){
		parent::init();

		$this->api->stickyGET('class');
		$this->memorize('class',$_GET['class']);

		$form=$this->add('Form',null,null,array('form_horizontal'));
		$class_field=$form->addField('dropdown','class')->setEmptyText('please select')->set($this->recall('class',null));
		$class=$this->add('Model_Class');
		$class->title_field='full_name';
		$class_field->setModel($class);
		$class_field->setAttr('class','hindi');
		$roll_field=$form->addField('line','roll_no')->validateNotNull();

		$form->addSubmit("Allot");

		$c=$this->add('Model_CurrentStudent');
		$c->addCondition('is_left',0);
		$c->_dsql()->del('order')->order('roll_no','asc');
		$crud=$this->add('CRUD',array('allow_add'=>false,"allow_del"=>false,"allow_edit"=>false));
		
		if($this->recall('class',0)){
			$c->addCondition('class_id',$this->recall('class'));
		}else{
			
			$c->addCondition('class_id',-1);

		}
			$c->setOrder('name','asc');

			$crud->setModel($c, array('name','roll_no'));
		if($crud->grid){
			$grid= $crud->grid;
			// $grid->addColumn('Expander','edit','Edit');
			$grid->addClass('reladable_grid');
			$grid->addFormatter('roll_no','grid/inline');
			$grid->js('reloadme',$grid->js()->reload());
			// $crud->grid->addPaginator();
			// $grid->addQuicksearch('roll_no');
			$class_field->js('change',$crud->grid->js()->reload(array('class'=>$class_field->js()->val())));

		}
		if($form->isSubmitted()){

			$students=$this->add('Model_CurrentStudent');
			$students->addCondition('class_id',$form->get('class'));
			$students->addCondition('is_left',0);
			// $students->_dsql()->del('order')->order('fname','asc');
			$start_roll_no=$form->get('roll_no');
			foreach ($students as $junk) {
				$students['roll_no'] = $start_roll_no ++;
				$students->save();
			}
			$crud->grid->js(null,$form->js()->reload())->reload(array("class"=>$form->get("class")))->execute();
		}

		


	}

	// function page_edit(){
	// 	$this->api->stickyGET('student_id');
	// 	$m=$this->add('Model_Students_Current');
	// 	$m->load($_GET['student_id']);
	// 	$form = $this->add('Form');
	// 	$form->setModel($m,array('roll_no'));
	// 	if($form->isSubmitted()){
	// 		$form->update();
	// 		$form->js()->univ()->successMessage('Upadetd')->closeExpander()->execute();
	// 		// $form->js()->_selector('.reladable_grid')->reload(array('class'=>$m['class_id']))->execute();
	// 	}
	// }
}
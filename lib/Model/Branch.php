<?php


class Model_Branch extends Model_Table{
public $table="branches";
	function init(){
		parent::init();

		$this->addField('name')->mandatory(true);
		$this->addField('address')->type('text')->mandatory(true);
		$this->addField('phone_no')->mandatory(true);
		$this->addField('principle_name');
		$this->addField('principle_contact_no');

		$this->addField('created_at')->type('date')->defaultValue($this->api->today)->system(true);
		$this->addField('update_at')->type('date')->system(true);

		$this->hasMany('Class','branch_id');
		$this->hasMany('Library_Subjects','branch_id');
		$this->hasMany('PaymentTransaction','branch_id');
		$this->hasMany('Stock_Transaction','branch_id');

		$this->addHook('beforeDelete',$this);

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function deleteForced(){
		$bc=$this->ref('Class');
		foreach ($bc as $junk) {
			$bc->delete($force);
		}
		$this->delete();
	}

	function beforeDelete(){
		if($this->ref('Class')->count()->getOne()>0)
			throw $this->exception(' You can Not delete, It contains classes');
		
	}

	function getCurrent(){
		$this->load($this->api->auth->model['branch_id']);
		return $this;
	}

	function createNew($branch_name,$other_fields=array(),$form=null){

		if($this->loaded())
			throw $this->exception("You can not use Loaded Model on createNewBranch ");
		
		$this['name']=$branch_name;
		unset($other_fields['name']);

		foreach ($other_fields as $key => $value) {
			$this[$key]=$value;
		}

		$this->save();			
	}

	function subjects($session=null){
		if(!$this->loaded())
			throw $this->exception("Model Must be loaded before getting subjects ");
		if(!$session) $session=$this->api->currentSession;

		$subjects=$this->add('Model_Subject');
		$subjects->filterByBranch($this);

		return $subjects;
  
	}

	function classes(){
		if(!$this->loaded())
			throw $this->exception('Branch must be loaded before getting Classes');

		$classes = $this->add('Model_Class');
		$classes->filterByBranch($this);

		return $classes;
	}


	function staffs(){
		if(!$this->loaded())
			throw $this->exception('Branch must be loaded before getting Staffs');

		$staff = $this->add('Model_Staff');
		$staff->filterByBranch($this);

		return $staff;
	}

	function library_subjects(){
		if(!$this->loaded())
			throw $this->exception('Branch must be loaded before getting Category');

		$categories = $this->add('Model_Library_Subjects');
		// $categories->filterByBranch($this);

		return $categories;
	}

	function library_items(){
		$li=$this->add('Model_Library_Item');
		$li->filterByBranch($this->api->currentBranch);
		return $li;
	}

}
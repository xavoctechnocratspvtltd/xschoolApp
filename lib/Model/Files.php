<?php

class Model_Files extends Model_Table {
	var $table= "files";
	function init(){
		parent::init();

		$this->add('filestore/Field_File','file_id');
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}
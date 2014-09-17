<?php

class page_master_session_promotestudent extends Page{
	function init(){
		parent::init();
		// throw new Exception($_GET['class'], 1);
		
		$this->js(true)->_selector('#s_menu')->hide();
		$this->add('View_Promote',array('class'=>$_GET['class']));
	}
}
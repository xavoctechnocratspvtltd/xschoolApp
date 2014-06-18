<?php


class xCRUD extends View_CRUD {

	function init(){
		parent::init();
	}

	function formSubmit($form){
		try {
			$hook_value = $this->hook('myupdate',array($form));
			if($hook_value[0]){
	            $self = $this;
	            $this->api->addHook('pre-render', function () use ($self) {
	                $self->formSubmitSuccess()->execute();
	            });
				return;	
			}else{
				
				return parent::formSubmit($form);
			}
        } catch (Exception_ValidityCheck $e) {
            $form->displayError($e->getField(), $e->getMessage());
        }
		return false;		
	}
}
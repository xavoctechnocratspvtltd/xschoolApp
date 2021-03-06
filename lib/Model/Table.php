<?php

class Model_Table extends SQL_Model {
	
	function init(){
		parent::init();

		// Log Editing Entries

		$self = $this;
		$this->addHook('beforeSave',function($model)use($self){
			// if(@$model->api->closing_running) return;
			
			if($model->loaded()){
				$old_m = $model->newInstance()->load($model->id);
				$changes=array();
				foreach ($model->dirty as $dirty_field=>$changed) {
					if($old_m[$dirty_field] != $model[$dirty_field])
						$changes[$dirty_field]=array('from'=>$old_m[$dirty_field],'to'=>$model[$dirty_field]);
				}

				if(!count($changes)) return;

				$log = $model->add('Model_Log');
				$log['branch_id'] = $self->api->currentBranch->id;
				$log['staff_id'] = $self->api->auth->model->id;
				$log['session_id']=$self->api->currentSession->id;
				$log['activity']=json_encode($changes);
				$log['model_class'] = get_class($model);
				$log['pk_id'] = $model->id;
				$log['type'] = "Edit";
				$log['url'] = (string) $self->api->url();
				$log->save(true);
			}
		});
		$this->addHook('beforeDelete',function($model)use($self){
				$log = $model->add('Model_Log');
				$log['branch_id'] = $self->api->currentBranch->id;
				$log['staff_id'] = $self->api->auth->model->id;
				$log['session_id']=$self->api->currentSession->id;
				$log['activity']=json_encode($model->data);
				$log['model_class'] = get_class($model);
				$log['pk_id'] = $model->id;
				$log['type'] = "Delete";
				$log['url'] = (string) $self->api->url();
				$log->save(true);
		});

	}
}
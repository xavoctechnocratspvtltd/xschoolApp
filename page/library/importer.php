<?php

class page_library_importer extends Page {
	function init(){
		parent::init();
		$form= $this->add('Form');
		$form->template->loadTemplateFromString("<form method='POST' action='?page=master_user_import&cut_page=1' enctype='multipart/form-data'>
			<input type='file' name='subscribers_file'/>
			<input type='submit' value='Upload'/>
			</form>
			<br/>
			<small><a href='epan-components/xEnquiryNSubscription/templates/subscribe.csv'>click here to download sample file</a></small>

			");

		if($_FILES['subscribers_file']){
			if ( $_FILES["subscribers_file"]["error"] > 0 ) {
				$this->add( 'View_Error' )->set( "Error: " . $_FILES["subscribers_file"]["error"] );
			}else{
				if($_FILES['subscribers_file']['type'] != 'text/csv'){
					$this->add('View_Error')->set('Only CSV Files allowed');
					return;
				}

				$importer = new CSVImporter($_FILES['subscribers_file']['tmp_name'],true,',');
				$data = $importer->get(); 



				$existing_staff = $this->add('Model_Staff');
				$existing_staff_array = $existing_staff->getRows();

				$stored_staff=array();
				foreach ($existing_staff_array as $esc) {
					$stored_staff[$esc['id']] = $esc['code'];
				}

				echo "<pre>";
					print_r($stored_staff);					
					print_r($data);					
					echo "</pre>";	

				foreach ($data as $d) {
					
					$staff_attendance = $this->add('Model_Staff_Attendance');
					$staff_attendance->addCondition('staff_id', array_search($d['code'], $stored_staff));
					// $staff_attendance->tryLoadAny();
					// $staff_attendance['subscribed_on'] = date('Y-m-d',strtotime($d['Subscribed On']));
					$staff_attendance->saveAndUnload();

				}

				$this->add('View_Info')->set(count($data).' Recored Imported');

			}
		}
	}
}
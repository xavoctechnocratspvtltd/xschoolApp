<?php

class page_library_importer extends Page {
	function init(){
		parent::init();
		
		$form= $this->add('Form');
		$form->template->loadTemplateFromString("<form method='POST' action='?page=library_importer' enctype='multipart/form-data'>
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
				if($_FILES['subscribers_file']['type'] != 'text/csv' and $_FILES['subscribers_file']['type'] != 'application/csv'){
					throw new Exception($_FILES['subscribers_file']['type'], 1);
					
					$this->add('View_Error')->set('Only CSV Files allowed');
					return;
				}

				$importer = new CSVImporter($_FILES['subscribers_file']['tmp_name'],true,',');
				$data = $importer->get(); 

				$existing_subjects = $this->add('Model_Library_Subjects');
				$existing_subjects_array = $existing_subjects->getRows();


				$existing_titles = $this->add('Model_Library_Subjects');
				$existing_titles_array = $existing_titles->getRows();

				$stored_subjects=array();
				$stored_titles=array();
				foreach ($existing_subjects as $es) {
					$stored_subjects[$es['id']] = $es['name'];
				}

				foreach ($existing_titles as $et) {
					$stored_titles[$et['id']] = $et['name'];
				}
				
				// print_r($data);
				// throw new Exception("Error Processing Request", 1);
				

				foreach ($data as $d) {
					foreach ($d as $key => $value) {
						$d[trim($key)] = trim($value);
					}

					if(!in_array($d['Subject'], $stored_subjects)){
						$new_subject = $this->add('Model_Library_Subjects');
						$new_subject['name'] = $d['Subject'];
						$new_subject->save();
						
						$stored_subjects[$new_subject->id] = $new_subject['name'];

						$new_subject->destroy();
					}

					if(!in_array($d['Title'], $stored_titles)){
						$new_title = $this->add('Model_Library_Title');
						$new_title['name'] = $d['Title'];
						$new_title['subject_id'] = array_search($d['Subject'], $stored_subjects);
						$new_title->save();
						
						$stored_titles[$new_title->id] = $new_title['name'];

						$new_title->destroy();
					}

					$new_item = $this->add('Model_Library_Item');
					$new_item->addCondition('title_id', array_search($d['Title'], $stored_titles));
					$new_item->addCondition('name', $d['AccessionNo']);
					$new_item->tryLoadAny();
					$new_item['book_no'] = $d['Book No'];
					$new_item['class_no'] = $d['Class No'];
					$new_item['publishe_year'] = $d['Publish Year'];
					$new_item['publisher'] = $d['Publisher'];
					$new_item['author'] = $d['Author'];
					$new_item['no_of_pages'] = $d['No of Pages'];
					$new_item['edition'] = $d['Edition'];
					$new_item['volume'] = $d['Volume'];
					$new_item['ISBN'] = $d['ISBN'];
					$new_item['bill_no'] = $d['Bill No'];
					$new_item['rate'] = $d['Rate'];
					$new_item['supplier_name'] =$d['Supplier Name'];
                    $new_item['branch_id'] = $this->api->currentBranch->id;
                    $new_item['is_issued'] = 0;
					$new_item->saveAndUnload();

				}

				$this->add('View_Info')->set(count($data).' Recored Imported');

			}
		}
	
	}
}
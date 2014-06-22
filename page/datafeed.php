<?php
class page_datafeed extends Page{
	
	function init(){
		parent::init();
		$this->add('H2')->set('Upload Student Record Excel File (.xls)');
			$file_path="fees.xls";

				include 'lib/excel_reader/excel_reader.php';       // include the class
				$Reader = new PhpExcelReader;      // creates object instance of the class
				$Reader->read('sch.xls');   // reads and stores the excel file data

				// Test to see the excel data stored in $sheets property
				// echo '<pre>';
				// var_export($excel->sheets);
				// echo '</pre>';
				$i=1;
				foreach ($Reader as $Row){

					if($i==1){
						$columns=$Row;
						$i++;
							continue;
					}
					// echo "<pre>";
					// print_r($Reader);
					// echo "</pre>";
					$scholar=$this->add('Model_Scholar');
					
					$c=0;
				    	foreach ($Row as $data) {
				    		if($columns[$c]=='id'){
				    			$c++;
				    			continue;
				    		}
					    	$scholar[$columns[$c]]=$data;
					    	// throw new \Exception($data);
					    	
					    	$c++;
				    	}
											

					$scholar->save();


					// if($columns['class']=="class")
						// throw new Exception($cell[0][19], 1);
						
					$i++;
				}
			}
			
			//$form->js()->reload()->execute();
	}
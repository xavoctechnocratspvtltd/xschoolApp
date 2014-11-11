<?php

class page_reports_cast extends Page {
	function init(){
		parent::init();

		$result = $this->add('Model_CurrentStudent')->addCondition('is_left',false)->countByCast();

		$grid= $this->add('Grid');

		$grid->setSource($result['count']);

		$grid->addColumn('class');
		foreach ($result['casts'] as $c) {
			$grid->addColumn($c);
		}

		$grid->add('misc/Export');
		

		

	}
}
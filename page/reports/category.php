<?php

class page_reports_category extends Page {
	function init(){
		parent::init();

		$result = $this->add('Model_CurrentStudent')->addCondition('is_left',false)->countByCategory();

		$grid= $this->add('Grid');

		$grid->setSource($result['count']);

		$grid->addColumn('class');
		foreach ($result['category'] as $c) {
			$grid->addColumn($c);
		}

		$grid->add('misc/Export');

	}
}
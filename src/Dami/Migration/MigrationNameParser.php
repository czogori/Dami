<?php

namespace Dami\Migration;

use Dami\Helper\StringHelper;

class MigrationNameParser
{
	private $migrationName;
	private $action;
	private $actionObject;
	private $model;
	
	private $availableActions = array('Create', 'Add', 'Drop', 'Remove');
	private $availableActionObjects = array('Table', 'Column', 'Index');

	public function setMigrationName($migrationName)
	{
		$this->migrationName = $migrationName;

		$items = explode('_', StringHelper::underscore($migrationName));		
		if(count($items) >= 3) {
			$this->action = $items[0];
			$this->actionObject = $items[1];
			$this->model = $items[2];
		}
	}

	public function getAction()
	{		
		return $this->action;
	}

	public function getActionObject()
	{		
		return $this->actionObject;
	}

	public function getModel()
	{
		return $this->model;
	}

}
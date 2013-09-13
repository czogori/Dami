<?php

namespace Dami\Migration;

class TemplateInitialization
{
	private $migrationNameParser;

	public function __construct(MigrationNameParser $migrationNameParser)
	{
		$this->migrationNameParser = $migrationNameParser;
	}

	public function setMigrationName($migrationName)
	{
		$this->migrationNameParser->setMigrationName($migrationName);
	}

	public function isAvailable()
	{
		return $this->migrationNameParser->getActionObject() && $this->migrationNameParser->getModel();
	}

	public function getInitUp()
	{
		return $this->isAvailable()
			? sprintf("\$this->create%s('%s');"
				, ucfirst($this->migrationNameParser->getActionObject())
				, $this->migrationNameParser->getModel())
			: '';
		
	}

	public function getInitDown()
	{
		return $this->isAvailable()
			? sprintf("\$this->drop%s('%s');"
				, ucfirst($this->migrationNameParser->getActionObject())
				, $this->migrationNameParser->getModel())
			: '';		
	}
}
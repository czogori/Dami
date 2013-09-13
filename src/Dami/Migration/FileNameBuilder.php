<?php

namespace Dami\Migration;

use Dami\Helper\StringHelper;

class FileNameBuilder
{
	private $migrationName;
	
	public function __construct($migrationName = '')
	{
		if('' === trim($migrationName)) {
			throw new \InvalidArgumentException('Migration name is required.');			
		}
		$this->migrationName = $migrationName;	
	}

	public function build($timestamp = null)
	{
		if(null === $timestamp) {
			$timestamp = (new \DateTime())->format('YmdHis');
		}
        $fileName = sprintf('%s_%s.php', $timestamp, StringHelper::underscore($this->migrationName));
        return $fileName;
	}
}
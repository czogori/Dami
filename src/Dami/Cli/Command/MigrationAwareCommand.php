<?php

namespace Dami\Cli\Command;
 
use Symfony\Component\Console\Command\Command;

use Dami\Migration;

abstract class MigrationAwareCommand extends Command
{
	private $migration;

    public function __construct($name = null, Migration $migration)
    {
    	parent::__construct($name);
        $this->migration = $migration;        
    }

    protected function getMigration()
    {
    	return $this->migration;
    }
}

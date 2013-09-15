<?php

namespace Dami\Cli\Command;
 
use Symfony\Component\Console\Command\Command;

use Dami\Container;

class AbstractCommand extends Command
{
	protected $container;

    public function __construct($name = null, Container $container)
    {
    	parent::__construct($name);
        $this->container = $container;        
    }
}

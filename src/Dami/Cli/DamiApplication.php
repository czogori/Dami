<?php

namespace Dami\Cli;
 
require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application,
    Dami\Cli\Command\CreateCommand,
    Dami\Cli\Command\MigrateCommand,
    Dami\Cli\Command\RollbackCommand,
    Dami\Cli\Command\StatusCommand;

use Dami\Container;    
 

class DamiApplication extends Application 
{
 
    public function __construct() {
        parent::__construct('Dami - [Da]tabase [mi]grations for PHP', '1.0');
    
        $this->addCommands(array(
            new CreateCommand(),
            new MigrateCommand(),
            new RollbackCommand(),
            new StatusCommand()
        ));
    }
}
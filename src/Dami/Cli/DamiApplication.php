<?php

namespace Dami\Cli;
 
require_once __DIR__ . '/../../../vendor/autoload.php';

use Symfony\Component\Console\Application,
    Dami\Cli\Command\CreateCommand,
    Dami\Cli\Command\MigrateCommand,
    Dami\Cli\Command\RollbackCommand,
    Dami\Cli\Command\StatusCommand;

use Dami\Container;    
use Dami\Migration;

class DamiApplication extends Application 
{
 
    public function __construct() {
        parent::__construct('Dami - [Da]tabase [mi]grations for PHP', '1.0');
    
        $container = new Container();
        $migration = new Migration($container);

        $this->addCommands(array(
            new CreateCommand('create', $container),
            new MigrateCommand('migrate', $migration),
            new RollbackCommand('rollback', $migration),
            new StatusCommand('status', $container)
        ));
    }
}
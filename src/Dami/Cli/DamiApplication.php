<?php

namespace Dami\Cli;
 
use Symfony\Component\Console\Application;
use Dami\Cli\Command\CreateCommand,
    Dami\Cli\Command\MigrateCommand,
    Dami\Cli\Command\RollbackCommand,
    Dami\Cli\Command\StatusCommand,
    Dami\Container,
    Dami\Migration;

class DamiApplication extends Application 
{
    public function __construct() 
    {
        parent::__construct('Dami - [Da]tabase [mi]grations for PHP', '0.3');
    
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
<?php

namespace Dami\Cli;

use Symfony\Component\Console\Application;
use Dami\Cli\Command\CreateCommand,
    Dami\Cli\Command\MigrateCommand,
    Dami\Cli\Command\RollbackCommand,
    Dami\Cli\Command\StatusCommand;

class DamiApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Dami - [Da]tabase [mi]grations for PHP', '0.9.1');

        $dami = new Dami();
        $container = $dami->getContainer();

        $this->addCommands(array(
            new CreateCommand('create', $container),
            new MigrateCommand('migrate', $container),
            new RollbackCommand('rollback', $container),
            new StatusCommand('status', $container)
        ));
    }
}

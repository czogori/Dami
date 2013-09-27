<?php

namespace Dami\Cli;

use Symfony\Component\Console\Application;
use Dami\Cli\Command\CreateCommand,
    Dami\Cli\Command\MigrateCommand,
    Dami\Cli\Command\RollbackCommand,
    Dami\Cli\Command\StatusCommand,
    Dami\Dami,
    Dami\Migration;

class DamiApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Dami - [Da]tabase [mi]grations for PHP', '0.3');

        $dami = new Dami();
        $migration = new Migration($dami->getContainer());

        $this->addCommands(array(
            new CreateCommand('create', $dami->getContainer()),
            new MigrateCommand('migrate', $migration),
            new RollbackCommand('rollback', $migration),
            new StatusCommand('status', $dami->getContainer())
        ));
    }
}

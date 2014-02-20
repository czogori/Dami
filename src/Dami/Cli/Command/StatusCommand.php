<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Dami\Migration\MigrationFiles;

class StatusCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setDescription('Migrations status.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationFiles = $this->getContainer()->get('dami.migration_files');
        $migrationFiles->statusIntention();
        $rows = array();
        foreach ($migrationFiles->get() as $migrationFile) {
            $status = $migrationFile->isMigrated() ? 'Migrated' : 'Not migrated';
            $rows[] = array($status, $migrationFile->getVersion(), $migrationFile->getName());
        }

        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Status', 'Version', 'Name'))
            ->setRows($rows)
            ->render($output);
    }
}

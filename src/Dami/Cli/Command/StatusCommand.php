<?php

namespace Dami\Cli\Command;
 
use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Dami\Migration\MigrationFiles;

class StatusCommand extends AbstractCommand
{    
    protected function configure()
    {
        $this            
            ->setDescription('Migrations status.');        
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $migrationFiles = $this->container->get('migration_files');
        
        $rows = array();
        foreach ($migrationFiles->getFiles() as $migrationFile) {
            $status = $migrationFile->isMigrated ? 'Migrated' : 'Not migrated';            
            $rows[] = array($status, $migrationFile->version, $migrationFile->name);
        }

        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Status', 'Version', 'Name'))
            ->setRows($rows)
            ->render($output);
    }
}
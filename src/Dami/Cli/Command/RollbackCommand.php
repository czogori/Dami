<?php

namespace Dami\Cli\Command;
 
use Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Output\OutputInterface;

use Dami\Migration;

class RollbackCommand extends Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setDescription('Rollback migrations.')
            ->addArgument('to-version', InputArgument::OPTIONAL, 'Rollback to specific version of migrations');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        $version = $input->getArgument('to-version');

        $migration = new Migration();
        $migration->rollback($version);
    }
}

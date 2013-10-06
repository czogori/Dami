<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Output\OutputInterface;

use Dami\Migration;

class RollbackCommand extends MigrationAwareCommand
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

        $migration = $this->getMigration();
        $numberMigrations = $migration->rollback($version);

        if ($numberMigrations > 0) {
            if ($numberMigrations == 1) {
                $output->writeln('<info>Rollback last migration success.</info>');
            } else {
                $output->writeln(sprintf('<info>Rollback %d migrations success.</info>', $numberMigrations));
            }
        } else {
            $output->writeln(sprintf('<comment>Nothing migrations detected to rollback.</comment>'));
        }
        
    }
}

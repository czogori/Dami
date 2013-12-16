<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Dami\Migration;

class MigrateCommand extends MigrationAwareCommand
{
    protected function configure()
    {
        $this
            ->setDescription('Migrate database.')
            ->addArgument('to-version', InputArgument::OPTIONAL, 'Migrate to specific version of migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument('to-version');
        $migration = $this->getMigration();
        $numberMigrations = $migration->migrate($version);

        if ($numberMigrations > 0) {
            $output->writeln('<info>Migration success.</info>');
        } else {
            $output->writeln(sprintf('<comment>Nothing migrations detected.</comment>'));
        }
    }
}

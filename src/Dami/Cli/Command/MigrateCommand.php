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
        parent::configure();

        $this
            ->setDescription('Migrate database.')
            ->addArgument('to-version', InputArgument::OPTIONAL, 'Migrate to specific version of migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $version = $input->getArgument('to-version');
        $migration = $this->getMigration();
        $numberMigrations = $migration->migrate($version);

        if ($numberMigrations > 0) {
            if ($numberMigrations == 1) {
                $output->writeln(sprintf('<info>%d migration was executed.</info>', $numberMigrations));
            } else {
                $output->writeln(sprintf('<info>%d migrations were executed.</info>', $numberMigrations));
            }
        } else {
            $output->writeln(sprintf('<comment>Nothing migrations detected.</comment>'));
        }
    }
}

<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends ContainerAwareCommand
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
        $migration = $this->getContainer()->get('dami.migration');

        $message = function($name, $version) use ($output) {
                $output->writeln(sprintf("\n<comment>Migration %s %s</comment>",
                    $version, $name));
        };
        $output->writeln('');
        try {
        $numberMigrations = $migration->migrate($version, $message);
        if ($numberMigrations > 0) {
            if ($numberMigrations == 1) {
                $output->writeln(sprintf("\n<info>%d migration was executed.</info>", $numberMigrations));
            } else {
                $output->writeln(sprintf("\n<info>%d migrations were executed.</info>", $numberMigrations));
            }
        } else {
            $output->writeln(sprintf('<comment>No migrations detected.</comment>'));
        }
      } catch(\PDOException $e) {
            $output->writeln("\n<error>There was something wrong during migration. Database schema has not been changed.</error>");
            if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
                    $output->writeln(sprintf("\n<error>%s</error>", $e->getMessage()));
            }
        }
    }
}

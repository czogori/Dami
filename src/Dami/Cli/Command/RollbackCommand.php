<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Output\OutputInterface;

class RollbackCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDescription('Rollback migrations.')
            ->addArgument('to-version', InputArgument::OPTIONAL, 'Rollback to specific version of migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $version = $input->getArgument('to-version');
        $migration = $this->getContainer()->get('dami.migration');
        if (null === $version) {
            $numberMigrations = $migration->migrateToPreviousVersion();
        } else {
            if ($version === 'all') {
                $version = 0;
            }
            $numberMigrations = $migration->migrate($version);
        }

        if ($numberMigrations > 0) {
            if ($numberMigrations == 1) {
                $output->writeln('<info>Rollback last migration.</info>');
            } else {
                $output->writeln(sprintf('<info>Rollback %d migrations.</info>', $numberMigrations));
            }
        } else {
            $output->writeln(sprintf('<comment>Nothing migrations detected to rollback.</comment>'));
        }

    }
}

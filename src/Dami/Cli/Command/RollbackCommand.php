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

        $message = function($name, $version) use ($output) {
                $output->write(sprintf("\n<comment>Migration %s %s</comment>",
                    $version, $name));
        };
        if (null === $version) {
            $numberMigrations = $migration->migrateToPreviousVersion($message);
        } else {
            if ($version === 'all') {
                $version = 0;
            }
            $numberMigrations = $migration->migrate($version, $message);
        }

        if (0 === $numberMigrations) {
            $output->writeln(sprintf("<comment>Nothing migrations detected to rollback.</comment>"));
        } elseif ($numberMigrations > 1) {
            $output->writeln(sprintf("\n<info>%d migrations were rollbacked.</info>", $numberMigrations));
        }
    }
}

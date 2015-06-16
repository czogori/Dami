<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;


class AbstractCommand extends Command
{
    protected function configure()
    {
        $this
            ->addOption('env', null, InputOption::VALUE_REQUIRED, 'Set environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $input->getOption('env');
        if (null !== $environment) {
            $config = $this->getContainer()->get('connection_config');
            $config->changeEnvironment($environment);
        }

        $migrationDirectory = $this->getContainer()->getparameter('dami.migrations_directory');
        if (!file_exists($migrationDirectory)) {
            mkdir($migrationDirectory);
        }
    }
}

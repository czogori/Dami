<?php

namespace Dami\Cli\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Filesystem\Filesystem;

use Dami\Migration\TemplateRenderer,
    Dami\Migration\FileNameBuilder;

class CreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setDescription('Create a new migration.')
            ->addArgument('migration_name', InputArgument::REQUIRED, 'Migration name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $arguments = $input->getArguments();
        $migrationName = $arguments['migration_name'];

        $filenameBuilder = new FileNameBuilder($migrationName);
        $fileSystem = new Filesystem();
        $templateRenderer = $this->getContainer()->get('dami.template_renderer');
        $migrationDirectory = $this->getContainer()->getparameter('dami.migrations_directory');

        try {
            $fileName = $filenameBuilder->build();
            $path = $migrationDirectory . '/' . $fileName;
            $fileSystem->dumpFile($path, $templateRenderer->render($migrationName));

            $output->writeln('<info>Migration has been created.</info>');
            $output->writeln(sprintf('<comment>Location: %s</comment>', $path));
        } catch (\Exception $e) {
            $output->writeln(sprintf("<error>Something went wrong.</error>\n\n%s", $e->getMessage()));
        }
    }
}

<?php

namespace Dami\Cli\Command;
 
use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Filesystem\Filesystem;    

use Dami\Migration\TemplateRenderer,
    Dami\Migration\FileNameBuilder,
    Dami\Container;
    
class CreateCommand extends Command
{    
    protected function configure()
    {
        $this
        ->setName('create')
        ->setDescription('Create a new migration.')
        ->setDefinition(array(
            new InputArgument('migration_name', InputArgument::REQUIRED, 'Migration name'),            
        ));             
    }
 
    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        $arguments = $input->getArguments();        
        $migrationName = $arguments['migration_name'];

        $filenameBuilder = new FileNameBuilder($migrationName);
        $fileName = $filenameBuilder->build();

        $fileSystem = new Filesystem();
        $container = new Container();

        $templateRenderer = $container->get('template_renderer');    
        
        // TODO load directory migration from config.yml
        $directory = 'migrations' . DIRECTORY_SEPARATOR;
        $path = $directory . $fileName;
        
        $fileSystem->dumpFile($path, $templateRenderer->render($migrationName));        
    }
}
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
        $this
            ->setDescription('Create a new migration.');
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

        $templateRenderer = $this->getContainer()->get('template_renderer');    
        $migrationDirectory = $this->getContainer()->getparameter('migrations_directory');        
        
        $path = $migrationDirectory . DIRECTORY_SEPARATOR . $fileName;        
        $fileSystem->dumpFile($path, $templateRenderer->render($migrationName));        
    }
}
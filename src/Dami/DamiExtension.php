<?php

namespace Dami;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class DamiExtension implements ExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(getcwd());
        try {
            $configFile = $fileLocator->locate('config.yml');            
            $config = Yaml::parse($configFile);                
            $migrationsDirectory = str_replace('@@DAMI_DIRECTORY@@', getcwd(), $config['migrations']);            
        } catch(\InvalidArgumentException $e) {
            // set default directory as current directory
            $migrationsDirectory = getcwd();
        }

        $container->setParameter('migrations_directory', $migrationsDirectory);
        $this->defineParameters($container);

        $definition = new Definition($container->getParameter('service_container.class'));
        $container->setDefinition('service_container', $definition);

        $definition = new Definition($container->getParameter('migration_name_parser.class'));
        $container->setDefinition('migration_name_parser', $definition);

        $definition = new Definition($container->getParameter('template_initialization.class'), array(new Reference('migration_name_parser')));
        $container->setDefinition('template_initialization', $definition);

        $definition = new Definition($container->getParameter('template_renderer.class'), array(new Reference('template_initialization')));
        $container->setDefinition('template_renderer', $definition);

        $definition = new Definition('Dami\Migration\SchemaTable');
        $definition->setArguments(array(new Reference('connection'), new Reference('schema.manipulation'), new Reference('schema.info')));
        $container->setDefinition('schema_table', $definition);

        $definition = new Definition('Dami\Migration\MigrationFiles');
        $definition->setArguments(array($migrationsDirectory, new Reference('schema_table')));
        $container->setDefinition('migration_files', $definition);
    }

    public function getAlias()
    {
        return 'dami';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }

    private function defineParameters(ContainerBuilder $container)
    {
        $container->setParameter('service_container.class', 'Dami\Container');
        $container->setParameter('api.class', 'Dami\Migration\Api\ApiMigration');
        $container->setParameter('template_renderer.class', 'Dami\Migration\TemplateRenderer');
        $container->setParameter('template_initialization.class', 'Dami\Migration\TemplateInitialization');
        $container->setParameter('migration_name_parser.class', 'Dami\Migration\MigrationNameParser');
    }
}

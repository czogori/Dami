<?php

namespace Dami\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class DamiExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(getcwd());
        try {
            $config = Yaml::parse(file_get_contents('config.yml'));
            $container->setParameter('dami.migrations_directory', str_replace('@@DAMI_DIRECTORY@@', getcwd(), $config['migrations']));
            $this->defineConnectionConfigParameter($container, $config);
        } catch (\InvalidArgumentException $e) {
            foreach ($configs as $config) {
                if (isset($config['migrations_directory'])) {
                    $container->setParameter('dami.migrations_directory', $config['migrations_directory']);
                }
            }
        }
        $this->defineParameters($container);
         if(!$container->hasParameter('dami.migrations_directory')) {
            $container->setParameter('dami.migrations_directory', $migrationsDirectory);
        }

        $definition = new Definition('%dami.migration_name_parser.class%');
        $container->setDefinition('dami.migration_name_parser', $definition);

        $definition = new Definition('%dami.template_initialization.class%',
            array(new Reference('dami.migration_name_parser')));
        $container->setDefinition('dami.template_initialization', $definition);

        $definition = new Definition('%dami.template_renderer.class%',
            array(new Reference('dami.template_initialization')));
        $container->setDefinition('dami.template_renderer', $definition);

        $definition = new Definition('Dami\Migration\SchemaTable');
        $definition->setArguments(array(new Reference('connection'),
            new Reference('rentgen.schema.manipulation'), new Reference('rentgen.schema.info')));
        $container->setDefinition('dami.schema_table', $definition);

        $definition = new Definition('Dami\Migration\MigrationFiles');
        $definition->setArguments(array('%dami.migrations_directory%', new Reference('dami.schema_table')));
        $container->setDefinition('dami.migration_files', $definition);

        $definition = new Definition('Dami\Migration');
        $definition->setArguments(array(new Reference('dami.schema_table'),
            new Reference('dami.migration_files'), new Reference('rentgen.schema.manipulation'), new Reference('rentgen.schema.info')));
        $container->setDefinition('dami.migration', $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'dami';
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }

    /**
     * Define parameters.
     *
     * @param ContainerBuilder $container
     *
     * @return void
     */
    private function defineParameters(ContainerBuilder $container)
    {
        $container->setParameter('dami.api.class', 'Dami\Migration\Api\ApiMigration');
        $container->setParameter('dami.template_renderer.class', 'Dami\Migration\TemplateRenderer');
        $container->setParameter('dami.template_initialization.class', 'Dami\Migration\TemplateInitialization');
        $container->setParameter('dami.migration_name_parser.class', 'Dami\Migration\MigrationNameParser');
    }

    /**
     * Define connection config parameters.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return void
     */
    private function defineConnectionConfigParameter(ContainerBuilder $container, $config)
    {
        $definition = new Definition('Rentgen\Database\Connection\ConnectionConfig');
        $definition->setArguments(array($config['environments']));
        $container->setDefinition('connection_config', $definition);
    }
}

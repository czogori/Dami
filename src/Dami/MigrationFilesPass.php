<?php

namespace Dami;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MigrationFilesPass implements CompilerPassInterface
{
    /**
     * Processes container.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $migrationFiles = $container->get('migration_files');
        $migrationFiles->setSchemaTable($container->get('schema_table'));
    }
}

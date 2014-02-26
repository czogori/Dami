<?php

namespace Dami\DependencyInjection\Compiler;

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
        $migrationFiles = $container->get('dami.migration_files');
        $migrationFiles->setSchemaTable($container->get('dami.schema_table'));
    }
}

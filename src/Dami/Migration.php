<?php

namespace Dami;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Dami\Migration\Direction;
use Dami\Migration\MigrationFiles;
use Dami\Migration\Api\MigrationApi;

class Migration
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function migrate()
    {
        return $this->execute(Direction::UP);
    }

    public function rollback($version = null)
    {
        return $this->execute(Direction::DOWN, $version);
    }

    private function execute($direction = Direction::UP, $version = null)
    {
        $schemaTable = $this->container->get('schema_table');
        $migrationFiles = $this->container->get('migration_files');
        $schemaManipulation = $this->container->get('schema.manipulation');
        $schemaInfo = $this->container->get('schema.info');

        if ($direction === Direction::UP) {
            $files = $migrationFiles->getFiles();
        } else {
            $files = '0' === $version ? $migrationFiles->getFilesInReverseOrder() : array($migrationFiles->getLatest());
        }

        $i = 0;
        foreach ($files as $file) {

            if (null === $file) {
                break;
            }
            if ($file->isMigrated && $direction == Direction::UP) {
                continue;
            }

            require_once $file->path;

            $migrationClass = $file->className;
            $definition = new $migrationClass($schemaManipulation, $schemaInfo);

            if ($direction == Direction::UP) {
                $definition->up();
            } else {
                $definition->down();
            }
            foreach ($definition->getActions() as $action) {
                if (!is_callable($action)) {
                    throw new InvalidArgumentException('Migration must be callable');
                }
                $action = call_user_func_array($action, array());
                if ($action instanceof MigrationApi) {
                    //echo get_class($action);
                    print_r($action);
                    $action->execute();
                }
            }
            if ($direction == Direction::UP) {
                $schemaTable->up($file->version);
            } else {
                $schemaTable->down($file->version);
            }
            $i++;
        }

        return $i;
    }
}

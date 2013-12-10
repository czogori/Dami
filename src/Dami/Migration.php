<?php

namespace Dami;

use Dami\Migration\Direction;
use Dami\Migration\MigrationFiles;
use Dami\Migration\SchemaTable;
use Dami\Migration\Api\MigrationApi;
use Rentgen\Schema\Info;
use Rentgen\Schema\Manipulation;

class Migration
{
    /**
     * @param SchemaTable $schemaTable description
     * @param MigrationFiles $migrationFiles description
     * @param Manipulation $schemaManipulation description
     * @param Info $schemaInfo description
     */
    public function __construct(SchemaTable $schemaTable, MigrationFiles $migrationFiles, Manipulation $schemaManipulation, Info $schemaInfo)
    {
        $this->schemaTable = $schemaTable;
        $this->migrationFiles = $migrationFiles;
        $this->schemaManipulation = $schemaManipulation;
        $this->schemaInfo = $schemaInfo;
    }

    /**
     * Migrate a schema.     
     * 
     * @return integer Number of migrations.
     */
    public function migrate()
    {
        return $this->execute(Direction::UP);
    }

    /**
     * Rollback a schema.
     *
     * @param string $version The version of migration to rollback.
     * 
     * @return integer Number of migrations.
     */
    public function rollback($version = null)
    {
        return $this->execute(Direction::DOWN, $version);
    }

    /**
     * Execute migrate or rollback.
     * 
     * @param string $direction Direction of migration (Direction::UP or Direction::DOWN)
     * @param string $version   The version of migration to rollback or migrate. 
     * 
     * @return integer Number of migrations.
     */
    private function execute($direction = Direction::UP, $version = null)
    {       
        if ($direction === Direction::UP) {
            $files = $this->migrationFiles->getFiles();
        } else {
            $files = '0' === $version ? $this->migrationFiles->getFilesInReverseOrder() : array($this->migrationFiles->getLatest());
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
            $definition = new $migrationClass($this->schemaManipulation, $this->schemaInfo);

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
                    print_r($action);
                    $action->execute();
                }
            }
            if ($direction == Direction::UP) {
                $this->schemaTable->up($file->version);
            } else {
                $this->schemaTable->down($file->version);
            }
            $i++;
        }

        return $i;
    }
}

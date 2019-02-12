<?php

namespace Dami;

use Dami\Migration\MigrationFiles;
use Dami\Migration\SchemaTable;
use Dami\Migration\Api\MigrationApi;
use Rentgen\Schema\Info;
use Rentgen\Schema\Manipulation;

class Migration
{
    /**
     * @param SchemaTable    $schemaTable        description
     * @param MigrationFiles $migrationFiles     description
     * @param Manipulation   $schemaManipulation description
     * @param Info           $schemaInfo         description
     */
    public function __construct(SchemaTable $schemaTable, MigrationFiles $migrationFiles, Manipulation $schemaManipulation, Info $schemaInfo)
    {
        $this->schemaTable = $schemaTable;
        $this->migrationFiles = $migrationFiles;
        $this->schemaManipulation = $schemaManipulation;
        $this->schemaInfo = $schemaInfo;
    }

    /**
     * Migrate the schema to the given version.
     *
     * @return integer Number of migrations.
     */
    public function migrate($version = null, $message = null, $up = true)
    {
        return $this->execute($version, $message, $up);
    }

    /**
     * Migrate the schema to previous version.
     *
     * @return integer Number of migrations.
     */
    public function migrateToPreviousVersion($message)
    {
        return $this->execute($this->schemaTable->getPreviousVersion(), $message, false);
    }

    /**
     * Create an instance of migration class.
     *
     * @param $className Name of migration class.
     *
     * @return mixed
     */
    protected function createMigrationApiInstance($className)
    {
        return new $className($this->schemaManipulation, $this->schemaInfo);
    }

    /**
     * Execute migrate.
     *
     * @param string $version The version of migration to rollback or migrate.
     *
     * @return integer Number of executed migrations.
     */
    private function execute($version = null, $message = null, $up = true)
    {
        $files = $this->migrationFiles->get($version, $up);
        if (null === $files) {
            return 0;
        }
        $this->schemaManipulation->execute('BEGIN');
        try {
            foreach ($files as $file) {

                require_once $file->getPath();

                $definition = $this->createMigrationApiInstance($file->getClassName());

                if ($message) {
                    $message($file->getName(), $file->getVersion());
                }
                if ($up) {
                    $definition->up();
                } else {
                    $definition->down();
                }
                foreach ($definition->getActions() as $action) {
                    if (!is_callable($action)) {
                        throw new \InvalidArgumentException('Migration must be callable');
                    }
                    $action = call_user_func_array($action, array());
                    if ($action instanceof MigrationApi) {
                        $action->execute();
                    }
                }
                $this->schemaTable->migrateToVersion($file->getVersion(), $up);
            }
            $this->schemaManipulation->execute('COMMIT');
        } catch (\Exception $e) {
            $this->schemaManipulation->execute('ROLLBACK');
            throw $e;
        }

        return count($files);
    }
}

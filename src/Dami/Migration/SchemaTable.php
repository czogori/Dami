<?php

namespace Dami\Migration;

use Rentgen\Database\Connection\Connection;
use Rentgen\Database\Table;
use Rentgen\Database\Column\DateTimeColumn;
use Rentgen\Database\Column\BigIntegerColumn;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Schema\Manipulation;
use Rentgen\Schema\Info;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class SchemaTable
{
    private $connection;
    private $manipulation;
    private $info;

    /**
     * Constructor.
     *
     * @param Connection   $connection   Connection instance.
     * @param Manipulation $manipulation Manipulation instance.
     * @param Info         $info         Info instance.
     */
    public function __construct(Connection $connection, Manipulation $manipulation, Info $info)
    {
        $this->connection = $connection;
        $this->manipulation = $manipulation;
        $this->info = $info;

        $this->createIfNotExists();
    }

    /**
     * Migrare to given version.
     *
     * @param string $version Version of migration.
     *
     * @return void
     */
    public function migrateToVersion($version)
    {
        $currentVersion = $this->getCurrentVersion();
        if((int) $version > $currentVersion) {
            $this->up($version);
        } else {
            $this->down($version);
        }
    }

    /**
     * Get all versions of migrations.
     *
     * @return array Versions of migrations.
     */
    public function getVersions()
    {
        $sql = 'SELECT version FROM schema_migration ORDER BY version DESC';
        $versions = array();
        foreach ($this->connection->query($sql) as $row) {
            $versions[] = $row['version'];
        }

        return $versions;
    }

    /**
     * Get current version of migration.
     *
     * @return integer
     */
    public function getCurrentVersion()
    {
        $versions = $this->getVersions();
        return count($versions) > 0 ? $versions[0] : 0;
    }

    /**
     * Get previous version of migration.
     *
     * @return integer
     */
    public function getPreviousVersion()
    {
        $versions = $this->getVersions();
        return count($versions) > 1 ? $versions[1] : 0;
    }

    /**
     * Add a new migrate entry.
     *
     * @param string $version Version of migration.
     *
     * @return void
     */
    private function up($version)
    {
        $sql = sprintf('INSERT INTO schema_migration (version,created_at) VALUES (%s, now())', $version);
        $this->connection->execute($sql);
    }

    /**
     * Delete a migrate entry.
     *
     * @param string $version Version of migration.
     *
     * @return void
     */
    private function down($version)
    {
        $sql = sprintf('DELETE FROM schema_migration WHERE version = \'%s\'', $version);
        $this->connection->execute($sql);
    }

    /**
     * Create schema table if not exists.
     *
     * @return void
     */
    private function createIfNotExists()
    {
        $table = new Table('schema_migration');
        if ($this->info->isTableExists($table)) {
            return;
        }
        $primaryKey = new PrimaryKey(array('version'));
        $primaryKey->disableAutoIncrement();

        $table->addConstraint($primaryKey);
        $table
            ->addColumn(new BigIntegerColumn('version'))
            ->addColumn(new DateTimeColumn('created_at', array('nullable' => false, 'default' => 'now()')));
        $this->manipulation->create($table);
    }
}

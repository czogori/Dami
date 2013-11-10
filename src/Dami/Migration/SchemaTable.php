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
     * Add a new migrate entry.
     *
     * @param string $version Version of migration.
     *
     * @return void
     */
    public function up($version)
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
    public function down($version)
    {
        $sql = sprintf('DELETE FROM schema_migration WHERE version = \'%s\'', $version);
        $this->connection->execute($sql);
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
        $this->manipulation->createTable($table);
    }
}

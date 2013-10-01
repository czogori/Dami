<?php

namespace Dami\Migration\Api;

use Dami\Migration\Api\Table;

use Rentgen\Schema\Manipulation;
use Rentgen\Schema\Info;
use Rentgen\Database\Constraint\PrimaryKey;

abstract class MigrationApi
{
    private $actions = null;
    private $manipulation;
    private $info;

    /**
     * Constructor.
     *
     * @param Manipulation $manipulation Manipulation instance.
     */
    public function __construct(Manipulation $manipulation, Info $info)
    {
        $this->actions = array();
        $this->manipulation = $manipulation;
        $this->info = $info;
    }

    /**
     * Create new table.
     *
     * @param string $name    Table name.
     * @param array  $options Optional options.
     *
     * @return Table Table instance.
     */
    public function createTable($name, array $options = array())
    {
        $table = new Table($name);
        $primaryKey = isset($options['primary_key'])
            ? new PrimaryKey($options['primary_key'])
            : new PrimaryKey();

        if (isset($options['schema'])) {
            $table->setSchema($options['schema']);
        }

        if (isset($options['primary_key_auto_increment']) && false === $options['primary_key_auto_increment']) {
            $primaryKey->disableAutoIncrement();
        }
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $table, $primaryKey) {
             return $manipulation->createTable($table, array($primaryKey));
         };

        return $table;
    }

    /**
     * Drop table.
     *
     * @param  string name    Table name.
     * @param array $options Optional options.
     *
     * @return void
     */
    public function dropTable($name, array $options = array())
    {
        $table = new Table($name);

        $cascade = isset($options['cascade']) ? $options['cascade'] : true;
        if (isset($options['schema'])) {
            $table->setSchema($options['schema']);
        }
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $table, $cascade) {
             return $manipulation->dropTable($table, $cascade);
         };
    }

    /**
     * Add foreign key.
     *
     * @return ForeignKey ForeignKey instance.
     */
    public function addForeignKey()
    {
        $foreignKey = new ForeignKey($this->info);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $foreignKey) {
            return $manipulation->addForeignKey($foreignKey);
        };

        return $foreignKey;
    }

    /**
     * Execute SQL.
     *
     * @param string $sql SQL to execute.
     *
     * @return integer
     */
    public function execute($sql)
    {
        return $this->manipulation->execute($sql);
    }

    /**
     * Get actions to execute.
     *
     * @return array Actions to execute.
     */
    public function getActions()
    {
        return $this->actions;
    }
}
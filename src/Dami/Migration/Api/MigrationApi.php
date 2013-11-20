<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;
use Rentgen\Schema\Info;

use Dami\Migration\Api\CreationTableApi;

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
     * @param string $name    CreationTableApi name.
     * @param array  $options Optional options.
     *
     * @return CreationTableApi CreationTableApi instance.
     */
    public function createTable($name, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;
        $table = new CreationTableApi($name, $schema);

        $primaryKey = isset($options['primary_key'])
            ? new PrimaryKey($options['primary_key'], $table)
            : new PrimaryKey(array(), $table);
        if (isset($options['primary_key_auto_increment']) && false === $options['primary_key_auto_increment']) {
            $primaryKey->disableAutoIncrement();
        }
        $table->addConstraint($primaryKey);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $table) {
             return $manipulation->createTable($table);
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
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;
        $table = new Table($name, $schema);

        $cascade = isset($options['cascade']) ? $options['cascade'] : true;
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $table, $cascade) {
             return $manipulation->dropTable($table, $cascade);
         };
    }

    public function alterTable($name, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        return new AlterationTableApi(new Table($name, $schema), $this->manipulation, $this->actions);
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

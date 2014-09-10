<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;
use Rentgen\Schema\Info;

abstract class MigrationApi
{
    private $actions = null;
    private $manipulation;
    private $info;

    /**
     * Constructor.
     *
     * @param Manipulation $manipulation Manipulation instance.
     * @param Info         $info         Info instance.
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
     * @return CreationTableApi CreationTableApi instance.
     */
    public function createTable($name, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $table = new TableApi($name, $schema);
        $table->init($this->manipulation, $this->actions);

        $primaryKey = isset($options['primary_key'])
            ? new PrimaryKey($options['primary_key'], $table)
            : new PrimaryKey(array(), $table);
        if (isset($options['primary_key_auto_increment']) && false === $options['primary_key_auto_increment']) {
            $primaryKey->disableAutoIncrement();
        }
        $table->addConstraint($primaryKey);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $table) {
             return $manipulation->create($table);
        };

        return $table;
    }

    /**
     * Drop table.
     *
     * @param string $name    Table name.
     * @param array  $options Optional options.
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
             return $manipulation->drop($table, $cascade);
         };
    }

    /**
     * Alter table.
     *
     * @param string $name    Table name.
     * @param array  $options Optional options.
     *
     * @return AlterationTableApi
     */
    public function alterTable($name, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;
        $table = new TableApi($name, $schema);
        $table->init($this->manipulation, $this->actions);

        return $table;
    }

    /**
     * Create new schema.
     *
     * @param string $name Schema name.
     *
     * @return void
     */
    public function createSchema($name)
    {
        $schema = new Schema($name);
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $schema) {
             return $manipulation->create($schema);
         };
    }

    /**
     * Drop schema.
     *
     * @param string $name Schema name.
     *
     * @return void
     */
    public function dropSchema($name)
    {
        $schema = new Schema($name);
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $schema) {
             return $manipulation->drop($schema);
         };
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

<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Column\DateTimeColumn;
use Rentgen\Database\Column\TextColumn;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Index;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;

class TableApi extends Table
{
    private $alterTable = false;

    /**
     * Constructor.
     *
     * @param string       $table        Table name.
     * @param Schema       $schema
     * @param Manipulation $manipulation
     * @param array        $actions
     */
    public function __construct($name, Schema $schema = null, Manipulation $manipulation, &$actions)
    {
        parent::__construct($name, $schema);
        $this->manipulation = $manipulation;
        $this->actions = &$actions;
    }

    /**
     * @param string $method A method name.
     * @param array  $params Parameters.
     *
     * @return TableApi Self.
     */
    public function __call($method, $params)
    {
        if ($method === 'addTimestamps') {
            $this->columns[] = new DateTimeColumn('created_at', array('not_null' => true));
            $this->columns[] = new DateTimeColumn('updated_at', array('not_null' => true));
        } else {
            $this->columns[] = (new ColumnFactory($method, $params))->createInstance();
        }
        
        return $this;
    }

    /**
     * Drop a column.
     *
     * @param string $name Column name.
     *
     * @return TableApi Self.
     */
    public function dropColumn($name)
    {
        $column = new TextColumn($name);
        $column->setTable($this);

        $this->actions[] =  function () use ($column) {
             return $this->manipulation->drop($column);
        };

        return $this;
    }

    /**
     * Add a foreign key.
     *
     * @param string $referenceTable   Referenced table name.
     * @param string $referenceColumns Columns of referenced table.
     * @param array  $options          Optional options.
     *
     * @return TableApi Self.
     */
    public function addForeignKey($referenceTable, $referenceColumns, array $options = [])
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this, new Table($referenceTable, $schema));
        $foreignKey->setReferencedColumns($referenceColumns);

        if (isset($options['column'])) {
            $foreignKey->setColumns($options['column']);
        } else {
            $foreignKey->setColumns($referenceColumns);
        }
        if (isset($options['update'])) {
            $foreignKey->setUpdateAction($options['update']);
        }
        if (isset($options['delete'])) {
            $foreignKey->setDeleteAction($options['delete']);
        }
        $this->actions[] =  function () use ($foreignKey) {
             return $this->manipulation->create($foreignKey);
        };

        return $this;
    }

    /**
     * Drop a foreign key.
     *
     * @param string $referenceTable   Referenced table name.
     * @param string $referenceColumns Columns of referenced table.
     * @param array  $options          Optional options.
     *
     * @return TableApi Self.
     */
    public function dropForeignKey($referenceTable, $referenceColumns, array $options = [])
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this, new Table($referenceTable, $schema));
        $foreignKey->setColumns($referenceColumns);
        $foreignKey->setReferencedColumns($referenceColumns);

        $this->actions[] =  function () use ($foreignKey) {
             return $this->manipulation->drop($foreignKey);
        };

        return $this;
    }

    /**
     * Add a unique constraint.
     *
     * @param array $columns Unique columns.
     *
     * @return TableApi Self.
     */
    public function addUnique($columns)
    {
        $unique = new Unique($columns, $this);

        $this->actions[] =  function () use ($unique) {
             return $this->manipulation->create($unique);
        };

        return $this;
    }

    /**
     * Drop a unique constraint.
     *
     * @param array $columns Unique columns.
     *
     * @return TableApi Self.
     */
    public function dropUnique($columns)
    {
        $unique = new Unique($columns, $this);

        $this->actions[] =  function () use ($unique) {
             return $this->manipulation->drop($unique);
        };

        return $this;
    }

    /**
     * Add a index on columns.
     *
     * @param array $columns Index columns.
     *
     * @return TableApi Self.
     */
    public function addIndex($columns)
    {
        $index = new Index($columns, $this);

        $this->actions[] =  function () use ($index) {
             return $this->manipulation->create($index);
        };

        return $this;
    }

    /**
     * Drop a index on columns.
     *
     * @param array $columns Index columns.
     *
     * @return TableApi Self.
     */
    public function dropIndex($columns)
    {
        $index = new Index($columns, $this);

        $this->actions[] =  function () use ($index) {
             return $this->manipulation->drop($index);
        };

        return $this;
    }
}

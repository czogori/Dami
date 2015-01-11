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
        switch ($method) {
            case 'addBigIntegerColumn':
            case 'addBinaryColumn':
            case 'addBooleanColumn':
            case 'addDateColumn':
            case 'addDateTimeColumn':
            case 'addDecimalColumn':
            case 'addFloatColumn':
            case 'addIntegerColumn':
            case 'addSmallIntegerColumn':
            case 'addStringColumn':
            case 'addTextColumn':
            case 'addTimeColumn':
                $namespace = 'Rentgen\\Database\\Column\\';
                $class = $namespace . ltrim($method, 'add');
                $options = isset($params[1]) ? $params[1] : array();
                $column = new $class($params[0], $options);

                if (isset($options['comment'])) {
                    $column->setDescription($options['comment']);
                }

                $this->columns[] = $column;
                break;
            case 'addTimestamps':
                $this->columns[] = new DateTimeColumn('created_at', array('not_null' => true));
                $this->columns[] = new DateTimeColumn('updated_at', array('not_null' => true));
                break;
            default:
                throw new \Exception(sprintf("Unsupported method " . $method));
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

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $column) {
             return $manipulation->drop($column);
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
    public function addForeignKey($referenceTable, $referenceColumns, array $options = array())
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
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $foreignKey) {
             return $manipulation->create($foreignKey);
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
    public function dropForeignKey($referenceTable, $referenceColumns, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this, new Table($referenceTable, $schema));
        $foreignKey->setColumns($referenceColumns);
        $foreignKey->setReferencedColumns($referenceColumns);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $foreignKey) {
             return $manipulation->drop($foreignKey);
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

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $unique) {
             return $manipulation->create($unique);
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

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $unique) {
             return $manipulation->drop($unique);
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

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $index) {
             return $manipulation->create($index);
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

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $index) {
             return $manipulation->drop($index);
        };

        return $this;
    }
}

<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Index;
use Rentgen\Database\Schema;
use Rentgen\Database\Table;
use Rentgen\Schema\Manipulation;

class AlterationTableApi
{
    private $alterTable = false;

    public function __construct(Table $table, Manipulation $manipulation, &$actions)
    {
        $this->table = $table;
        $this->manipulation = $manipulation;
        $this->actions = &$actions;
    }

    /**
     * @param string $method A method name.
     * @param array  $params Parameters.
     *
     * @return CreationTableApi Self.
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
                break;
            case 'addTimestamps':
                $column = new DateTimeColumn('created_at', array('not_null' => true));
                $column = new DateTimeColumn('updated_at', array('not_null' => true));
                break;
            default:
                throw new \Exception(sprintf("Unsupported method " . $method));
        }
        $column->setTable($this->table);
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $column) {
             return $manipulation->create($column);
        };

        return $this;
    }

    public function addForeignKey($referenceTable, $referenceColumns, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this->table, new Table($referenceTable, $schema));
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

    public function dropForeignKey($referenceTable, $referenceColumns, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this->table, new Table($referenceTable, $schema));
        $foreignKey->setColumns($referenceColumns);
        $foreignKey->setReferencedColumns($referenceColumns);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $foreignKey) {
             return $manipulation->drop($foreignKey);
        };

        return $this;
    }

    public function addUnique($columns)
    {
        $unique = new Unique($columns, $this->table);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $unique) {
             return $manipulation->create($unique);
        };

        return $this;
    }

    public function dropUnique($columns)
    {
        $unique = new Unique($columns, $this->table);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $unique) {
             return $manipulation->drop($unique);
        };

        return $this;
    }

    public function addIndex($columns)
    {
        $index = new Index($columns, $this->table);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $index) {
             return $manipulation->create($index);
        };

        return $this;
    }

    public function dropIndex($columns)
    {
        $index = new Index($columns, $this->table);

        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $index) {
             return $manipulation->drop($index);
        };

        return $this;
    }
}

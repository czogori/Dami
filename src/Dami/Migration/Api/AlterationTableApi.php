<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Table;
use Rentgen\Database\Schema;
use Rentgen\Schema\Manipulation;
use Rentgen\Database\Column\StringColumn;

class AlterationTableApi
{    
    private $alterTable = false;

    public function __construct(Table $table, Manipulation $manipulation, &$actions)
    {
        $this->table = $table;
        $this->manipulation = $manipulation;
        $this->actions = &$actions;
    }

    public function addColumn($name, $options)
    {
        $manipulation = $this->manipulation;
        $table = $this->table;
        $column = new StringColumn($name, $options);
        $column->setTable($this->table);
        $this->actions[] =  function () use ($manipulation, $column) {
             return $manipulation->addColumn($column);
        };        
        return $this;
    }

    public function dropColumn($name)
    {
        $manipulation = $this->manipulation;
        $table = $this->table;
        $column = new StringColumn($name);
        $column->setTable($this->table);
        $this->actions[] =  function () use ($manipulation, $column) {
             return $manipulation->dropColumn($column);
        };
        return $this;
    }

    public function addForeignKey($referenceTable, $referenceColumns, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;     

        $foreignKey = new ForeignKey($this->table, new Table($referenceTable, $schema));        
        $foreignKey->setColumns($referenceColumns);        
        $foreignKey->setReferencedColumns($referenceColumns);
        
        $manipulation = $this->manipulation;
        $this->actions[] =  function () use ($manipulation, $foreignKey) {
             return $manipulation->addConstraint($foreignKey);
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
             return $manipulation->dropConstraint($foreignKey);
        };
        return $this;
    }
}
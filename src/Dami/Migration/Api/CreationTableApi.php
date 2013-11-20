<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Table;
use Rentgen\Database\Schema;
use Rentgen\Database\Constraint\ForeignKey;
use Rentgen\Database\Constraint\Unique;
use Rentgen\Database\Column\DateTimeColumn;

class CreationTableApi extends Table
{   
    /**
     * @param string $method A method name.
     * @param array  $params Parameters.
     * 
     * @return CreationTableApi Self.
     */
    public function __call($method, $params)
    {
        switch ($method) {
            case 'addStringColumn':
            case 'addTextColumn':
            case 'addIntegerColumn':
            case 'addBooleanColumn':
            case 'addDateTimeColumn':
            case 'addDateColumn':
                $namespace = 'Rentgen\\Database\\Column\\';
                $class = $namespace . ltrim($method, 'add');
                $options = isset($params[1]) ? $params[1] : array();
                $this->columns[] = new $class($params[0], $options);
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
     * Adds a foreign key.
     * 
     * @param string $referenceTable   Referenced table name.
     * @param string $referenceColumns Columns of referenced table.
     * @param array  $options          Optional options.
     * 
     * @return CreationTableApi Self.
     */
    public function addForeignKey($referenceTable, $referenceColumns, array $options = array())
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;

        $foreignKey = new ForeignKey($this, new Table($referenceTable, $schema));
        $foreignKey->setColumns($referenceColumns);
        $foreignKey->setReferencedColumns($referenceColumns);
        
        $this->addConstraint($foreignKey);

        return $this;
    }

    /**
     * 
     * @param array|string $columns Columns of table.
     * 
     * @return CreationTableApi Self.
     */
    public function addUnique($columns)
    {        
        $this->addConstraint(new Unique($columns, $this));

        return $this;
    }
}

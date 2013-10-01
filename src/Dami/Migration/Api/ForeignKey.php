<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\ForeignKey as RentgenForeignKey;
use Rentgen\Database\Table as RentgenTable;
use Rentgen\Schema\Info;

class ForeignKey extends RentgenForeignKey
{
    private $info;

    public function __construct(Info $info)
    {
        $this->info = $info;
    }

    public function foreignKey($tableName, $columnNames, array $options = array())
    {
        $table = new RentgenTable($tableName);

        if (isset($options['schema'])) {
            $table->setSchema($options['schema']);
        }
        $this->setTable($table);
        $this->setColumns($columnNames);

        return $this;
    }

    public function reference($tableName, $columnNames, array $options = array())
    {
        $table = new RentgenTable($tableName);

        if (isset($options['schema'])) {
            $table->setSchema($options['schema']);
        }
        $this->setReferencedTable($table);
        $this->setReferencedColumns($columnNames);

        return $this;
    }
}

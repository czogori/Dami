<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\ForeignKey as RentgenForeignKey;
use Rentgen\Database\Table as RentgenTable;
use Rentgen\Database\Schema;
use Rentgen\Schema\Info;

class ForeignKey extends RentgenForeignKey
{
    private $info;

    public function __construct(Info $info)
    {
        $this->info = $info;
    }

    public function foreignKey($tableName, $columnNames, array $options = [])
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;
        $table = new RentgenTable($tableName, $schema);

        $this->setTable($table);
        $this->setColumns($columnNames);

        return $this;
    }

    public function reference($tableName, $columnNames, array $options = [])
    {
        $schema = isset($options['schema']) ? new Schema($options['schema']) : null;
        $table = new RentgenTable($tableName, $schema);

        $this->setReferencedTable($table);
        $this->setReferencedColumns($columnNames);

        return $this;
    }
}

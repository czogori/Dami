<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Table as RentgenTable;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\Column\DateColumn;
use Rentgen\Database\Column\DateTimeColumn;
use Rentgen\Database\Column\BooleanColumn;
use Rentgen\Database\Column\TextColumn;

class Table extends RentgenTable
{
    public function __call($method, $params) 
    {
        switch($method) {
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
                return $this;
            case 'addTimestamps':
                $this->columns[] = new DateTimeColumn('created_at', array('not_null' => true));
                $this->columns[] = new DateTimeColumn('updated_at', array('not_null' => true));
                return $this;
            default:
                throw new Exception(sprintf("Unsupported method " . $method));                
        }
    }
}

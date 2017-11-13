<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Column\CustomColumn;

class ColumnFactory
{
    /**
     * @param string $method A method name.
     * @param array  $params Parameters.
     */
    public function __construct($method, $params)
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * Create a new instance.
     *
     * @return Rentgen\Database\Column
     */
    public function createInstance()
    {
        switch ($this->method) {
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
                $class = $namespace . ltrim($this->method, 'add');
                $options = isset($this->params[1]) ? $this->params[1] : array();

                if ('addStringColumn' === $this->method && !isset($options['limit'])) {
                    $options['limit'] = 255;
                }
                $column = new $class($this->params[0], $options);
                break;
            case 'addCustomColumn':
                $options = isset($this->params[2]) ? $this->params[2] : [];
                $column = new CustomColumn($this->params[0], $this->params[1], $options);
                break;
            default:
                throw new \Exception(sprintf("Unsupported method " . $this->method));
        }

        if (isset($options['comment'])) {
            $column->setDescription($options['comment']);
        }

        return $column;
    }
}

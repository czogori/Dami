<?php

namespace Dami\Migration\Api;

class ColumnFactory
{
    /**
     * @param string $method A method name.
     * @param array  $params Parameters.
     *
     * @return TableApi Self.
     */
    public function __construct($method, $params)
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     *
     * @return TableApi Self.
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

                if (isset($options['comment'])) {
                    $column->setDescription($options['comment']);
                }
                break;
            default:
                throw new \Exception(sprintf("Unsupported method " . $this->method));
        }

        return $column;
    }
}

<?php

namespace Dami\Migration;

use Dami\Helper\StringHelper;

class FilenameParser
{
    private $version;

    public function __construct($filename)
    {
        $items = explode('_', $filename);
        $this->version = $items[0];

        $this->name = ltrim($filename, $this->version . '_');
        $this->name = rtrim($this->name, '.php');
        $this->className = StringHelper::camelize($this->name) . 'Migration';
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getMigrationName()
    {
        return $this->name;
    }

    public function getMigrationClassName()
    {
        return $this->className;
    }
}

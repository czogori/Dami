<?php

namespace Dami\Migration;

use Stringy\StaticStringy as S;

class FileNameParser
{
    private $version;

    /**
     * Constructor.
     *
     * @param string $filename Filename of migration.
     */
    public function __construct($filename)
    {
        $items = explode('_', $filename);
        $this->version = $items[0];

        $this->name = ltrim($filename, $this->version . '_');
        $this->name = basename($this->name, '.php');
        $this->className = S::upperCamelize($this->name) . 'Migration';
    }

    /**
     * Get version of migration.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get name of migration.
     *
     * @return string
     */
    public function getMigrationName()
    {
        return $this->name;
    }

    /**
     * Get class name of migration.
     *
     * @return string
     */
    public function getMigrationClassName()
    {
        return $this->className;
    }
}

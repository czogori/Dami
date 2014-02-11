<?php

namespace Dami\Migration;

class MigrationFile
{
    private $name;
    private $path;
    private $version;
    private $className;
    private $isMigrated;

    /**
     * @param string $name       Name of migration.
     * @param string $path       Path of migration.
     * @param string $version    Version of migration.
     * @param string $className  Class name of migration.
     * @param bool   $isMigrated If migration is migrated.
     */
    public function __construct($name, $path, $version, $className, $isMigrated = false)
    {
        $this->name = $name;
        $this->path = $path;
        $this->version = $version;
        $this->className = $className;
        $this->isMigrated = $isMigrated;
    }

    /**
     * Gets name of migration.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets path of migration.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Gets version of migration.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets class name of migration.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Check if migration is migrated.
     *
     * @return bool
     */
    public function isMigrated()
    {
        return $this->isMigrated;
    }
}

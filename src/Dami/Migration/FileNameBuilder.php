<?php

namespace Dami\Migration;

use Dami\Helper\StringHelper;

class FileNameBuilder
{
    private $migrationName;

    /**
     * Constructor.
     *
     * @param string $migrationName Migration name.
     *
     * @throws InvalidArgumentException If the $migrationName is missing or null.
     */
    public function __construct($migrationName = null)
    {
        if (null === $migrationName) {
            throw new \InvalidArgumentException('Migration name is required.');
        }
        $this->migrationName = $migrationName;
    }

    /**
     * Build migration file name.
     *
     * @param datatype $timestamp Timestamp of migration.
     *
     * @return string
     */
    public function build($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = new \DateTime();
            $timestamp = $timestamp->format('YmdHis');
        }
        $fileName = sprintf('%s_%s.php', $timestamp, StringHelper::underscore($this->migrationName));

        return $fileName;
    }
}

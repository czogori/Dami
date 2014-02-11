<?php

namespace Dami\Migration;

use Symfony\Component\Finder\Finder;

class MigrationFiles
{
    private $path;
    private $schemaTable;
    private $statusIntention = false;

    /**
     * @param string      $path        Migrations path.
     * @param SchemaTable $schemaTable SchemaTable instance.
     */
    public function __construct($path, SchemaTable $schemaTable)
    {
        $this->path = $path;
        $this->schemaTable = $schemaTable;
        $this->currentVersion = $this->schemaTable->getCurrentVersion();
    }

    /**
     * Gets migration files.
     *
     * @param string $version Version of migration.
     *
     * @return MigrationFile[]
     */
    public function get($version = null)
    {
        if ($version === $this->currentVersion) {
            return null;
        }
        $migrateUp = null === $version || $version >= $this->currentVersion;
        $migrationFiles = array();
        foreach ($this->getFiles($migrateUp) as $file) {
            $filenameParser = new FileNameParser($file->getFileName());

            $isMigrated = in_array($filenameParser->getVersion(), $this->schemaTable->getVersions());

            $migrationFile = new MigrationFile($filenameParser->getMigrationName(), $file->getRealpath(),
                $filenameParser->getVersion(), $filenameParser->getMigrationClassName(), $isMigrated);

            if (false === $this->statusIntention) {
                if ($migrateUp && $isMigrated
                    || !$migrateUp && !$isMigrated) {
                    continue;
                }
                if ($version == $migrationFile->getVersion()) {
                    if ($migrateUp) {
                        $migrationFiles[] = $migrationFile;
                    }
                    break;
                }
            }
            $migrationFiles[] = $migrationFile;
        }

        return $migrationFiles;
    }

    /**
     * This method is called when status of migrations is checking.
     *
     * @return MigrationFiles
     */
    public function statusIntention()
    {
        $this->statusIntention = true;

        return $this;
    }

    /**
     * Gets files from directory.
     *
     * @param bool $migrateUp Is migration up.
     *
     * @return bool
     */
    private function getFiles($migrateUp)
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->in($this->path)
            ->sort(function (\SplFileInfo $a, \SplFileInfo $b) use ($migrateUp) {
                return $migrateUp
                    ? $a->getRealpath() > $b->getRealpath()
                    : $a->getRealpath() < $b->getRealpath();
            }
        );
    }
}

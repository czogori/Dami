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
    }

    /**
     * Gets migration files.
     *
     * @param string $version Version of migration.
     *
     * @return MigrationFile[]
     */
    public function get($version = null, $up = true)
    {
        $currentVersion = $this->schemaTable->getCurrentVersion();
        if (null !== $version && false === $up && (int) $version > (int) $currentVersion) {
            return null;
        }

        $files = $this->getFiles($up);

        if (!$this->fileVersionExists($version, $this->getFiles($up))) {
            return null;
        }

        $migrationFiles = array();
        foreach ($files as $file) {
            $filenameParser = new FileNameParser($file->getFileName());
            $isMigrated = in_array($filenameParser->getVersion(), $this->schemaTable->getVersions());
            $migrationFile = new MigrationFile($filenameParser->getMigrationName(), $file->getRealpath(),
                $filenameParser->getVersion(), $filenameParser->getMigrationClassName(), $isMigrated);

            if (false === $this->statusIntention) {
                if ($up && $isMigrated || !$up && !$isMigrated) {
                    continue;
                }
                if ($version == $migrationFile->getVersion()) {
                    $migrationFiles[] = $migrationFile;
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

    /**
     * Gets files from directory.
     *
     * @param bool  $version Version of migration.
     * $param array $files   Migration files
     *
     * @return bool
     */
    private function fileVersionExists($version, $files)
    {
        if (null === $version || 'all' == $version) {
            return true;
        }

        foreach ($files as $file) {
            $filenameParser = new FileNameParser($file->getFileName());
            if ($version === $filenameParser->getVersion()) {
                return true;
            }
        }

        return false;
    }
}

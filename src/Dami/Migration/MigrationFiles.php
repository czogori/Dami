<?php

namespace Dami\Migration;

use Symfony\Component\Finder\Finder;

class MigrationFiles
{
    private $path;
    private $schemaTable;

    public function __construct($path, SchemaTable $schemaTable)
    {
        $this->path = $path;
        $this->schemaTable = $schemaTable;
    }

    public function getFiles()
    {
        $files = array();
        foreach ($this->getMigrationFiles() as $file) {
            $filenameParser = new FileNameParser($file->getFileName());
            $isMigrated = in_array($filenameParser->getVersion(), $this->schemaTable->getVersions());

            $migrationFile = new MigrationFile();
            $migrationFile->name = $filenameParser->getMigrationName();
            $migrationFile->path = $file->getRealpath();
            $migrationFile->version = $filenameParser->getVersion();
            $migrationFile->isMigrated = $isMigrated;
            $migrationFile->className = $filenameParser->getMigrationClassName();
            $files[] = $migrationFile;
        }

        return $files;
    }

    public function getMigratedFiles($version = null)
    {
        $migratedFiles = array();
        foreach ($this->getFiles() as $file) {
            if ($file->isMigrated) {
                $migratedFiles[] = $file;
            }
        }

        return $migratedFiles;
    }

    public function getLatest()
    {
        $files = $this->getFilesInReverseOrder();

        return $files ? $files[0] : null;
    }

    public function getFilesInReverseOrder()
    {
        return array_reverse($this->getMigratedFiles());
    }

    private function getMigrationFiles()
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->in($this->path)
            ->sortByName();
    }
}

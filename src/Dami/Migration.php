<?php

namespace Dami;


use Dami\Migration\Direction;
use Dami\Migration\FilenameParser;
use Dami\Migration\MigrationFiles;
use Dami\Migration\Api\MigrationApi;

class Migration
{	
	public function migrate()
	{	
		return $this->execute(Direction::UP);
	}

	public function rollback($version = null)
	{		
		$this->execute(Direction::DOWN, $version);
	}

	private function execute($direction = Direction::UP, $version = null)
	{				
		$container = new Container();
		$schemaTable = $container->get('schema_table');				
		$migrationFiles = $container->get('migration_files');
		$schemaManipulation = $container->get('schema.manipulation');

		if(Direction::UP) {
			$files = $migrationFiles->getFiles();
		} else {
			$files = 0 === $version ? $migrationFiles->getFilesInReverseOrder() : array($migrationFiles->getLatest());
		}

		$i = 0;

		foreach ($files as $file) {    			
			
			if(null === $file) {
				break;
			}
			if($file->isMigrated && $direction == Direction::UP) {				
				continue;
			}

			require_once $file->path;
			
			$migrationClass = $file->className;
			$definition = new $migrationClass($schemaManipulation);		

			if($direction == Direction::UP) {
				$definition->up();	
			} else {
				$definition->down();	
			}								
			foreach ($definition->getActions() as $action) {								
				if (!is_callable($action)) {
	            	throw new InvalidArgumentException('Migration must be callable');
	        	}	        	        	
	        	$action = call_user_func_array($action, array());				
				if($action instanceof MigrationApi) {
					//echo get_class($action);
					print_r($action);
					$action->execute();
				}  
			}
			if($direction == Direction::UP) {				
				$schemaTable->up($file->version);
			} else {
				$schemaTable->down($file->version);
			}	
			$i++;		
		}
		return $i;
	}
}




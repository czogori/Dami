<?php

namespace Dami;


use Dami\Migration\Direction;
use Dami\Migration\FilenameParser;
use Dami\Migration\MigrationFiles;
use Dami\Migration\Api\MigrationApi;

abstract class AbstractMigration
{
	protected function execute($direction = Direction::UP)
	{		
		$container = new Container();
		$schemaTable = $container->get('schema_table');				
		$migrationFiles = $container->get('migration_files');
		$files = $direction == Direction::UP ?
			$migrationFiles->getFiles() :
			$migrationFiles->getFilesInReverseOrder();		
		$i = 0;
		foreach ($files as $file) {    			
			
			if($file->isMigrated && $direction == Direction::UP) {				
				continue;
			}

			require_once $file->path;
			
			$migrationClass = $file->className;
			$definition = new $migrationClass($container->get('schema.manipulation'));		

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




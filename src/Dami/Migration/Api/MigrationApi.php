<?php

namespace Dami\Migration\Api;

use Dami\Container;
use Dami\Migration\Api\Table;

use Rentgen\Schema\Manipulation;
use Rentgen\Database\Constraint\PrimaryKey;

abstract class MigrationApi
{	
	public $actions = null;
	private $manipulation;

	public function __construct(Manipulation $manipulation)
	{						
		$this->actions = array();
		$this->manipulation = $manipulation;
	}

	public function createTable($name, array $options = array())
	{		
		$table = new Table($name);					
		$primaryKey = isset($options['primary_key'])
			? new PrimaryKey($options['primary_key'])
			: new PrimaryKey();

		$this->actions[] =  function () use($table, $primaryKey) {
    		return $this->manipulation->createTable($table, array($primaryKey));    			
    	};
		return $table;
	}

	public function dropTable($name)
	{		
		$table = new Table($name);			
		$this->actions[] =  function () use($table) {
    		return $this->manipulation->dropTable($table, true);    			
    	};
		return $table;
	}	

	public function addForeignKey()	
	{
		$foreignKey = new ForeignKey(new Table('foo'), new Table('fof'));
		
		$this->actions[] =  function () use($foreignKey) {
    		return $this->manipulation->addForeignKey($foreignKey);    			
    	};
		return $foreignKey;
	}

	public function getActions()
	{
		return $this->actions;
	}
}


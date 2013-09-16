<?php

namespace Dami\Migration\Api;

use Dami\Container;
use Dami\Migration\Api\Table;

use Rentgen\Schema\Manipulation;
use Rentgen\Database\Constraint\PrimaryKey;

abstract class MigrationApi
{	
	private $actions = null;
	private $manipulation;

	/**
	 * Constructor.
	 * 
	 * @param Manipulation $manipulation Manipulation instance.
	 */
	public function __construct(Manipulation $manipulation)
	{						
		$this->actions = array();
		$this->manipulation = $manipulation;
	}

	/**
	 * Create new table.
	 * 
	 * @param  string $name    Table name.
	 * @param  array  $options Optional options.
	 * 
	 * @return Table           Table instance.
	 */
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

	/**
	 * Drop table.
	 * 
	 * @param  string  $name    Table name.
	 * @param  boolean $cascade If delete cascade.
	 * 
	 * @return void
	 */
	public function dropTable($name, $cascade = true)
	{		
		$table = new Table($name);			
		$this->actions[] =  function () use($table, $cascade) {
    		return $this->manipulation->dropTable($table, $cascade);    			
    	};		
	}	

	/**
	 * Add foreign key.
	 *
	 * @return ForeignKey ForeignKey instance.
	 */
	public function addForeignKey()	
	{
		$foreignKey = new ForeignKey(new Table('foo'), new Table('fof'));
		
		$this->actions[] =  function () use($foreignKey) {
    		return $this->manipulation->addForeignKey($foreignKey);    			
    	};
		return $foreignKey;
	}

	/**
	 * Execute SQL.
	 * 
	 * @param  string $sql SQL to execute.
	 * 
	 * @return integer
	 */
	public function execute($sql)
	{
		return $this->manipulation->execute($sql);
	}

	/**
	 * Get actions to execute.
	 * 
	 * @return array Actions to execute.
	 */
	public function getActions()
	{
		return $this->actions;
	}
}


<?php

namespace Dami\Migration;

use Dami\Container;
use Rentgen\Database\Connection\Connection;
use Rentgen\Database\Table;
use Rentgen\Database\Column\DateTimeColumn;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\Constraint\PrimaryKey;
use Rentgen\Schema\Manipulation;
use Rentgen\Schema\Info;

class SchemaTable
{
	private $connection;
	private $container;
	private $manipulation;
	private $info;

	/**
	 * [__construct description]
	 * @param Connection   $connection   [description]
	 * @param Manipulation $manipulation [description]
	 * @param Info         $info         [description]
	 */
	public function __construct(Connection $connection, Manipulation $manipulation, Info $info)
	{
		$this->connection = $connection;
		$this->manipulation = $manipulation;
		$this->info = $info;

		$this->createIfNotExists();
	}

	/**
	 * [up description]
	 * @param  [type] $version [description]
	 * @return [type]          [description]
	 */
	public function up($version)
	{
		//$this->createIfNotExists();
		$sql = sprintf('INSERT INTO schema_migration (version,created_at) VALUES (%s, now())', $version);
		$this->connection->execute($sql);
	}

	/**
	 * [down description]
	 * @param  [type] $version [description]
	 * @return [type]          [description]
	 */
	public function down($version)
	{
		$this->deleteVersion($version);
	}

	/**
	 * [getVersions description]
	 * @return [type] [description]
	 */
	public function getVersions()
	{
		//$this->createIfNotExists();
		$sql = 'SELECT version FROM schema_migration ORDER BY version DESC';		
		$versions = array();
		foreach ($this->connection->query($sql) as $row) {
			$versions[] = $row['version'];
    	}    	
    	return $versions;
	}

	/**
	 * [deleteVersion description]
	 * @param  [type] $version [description]
	 * @return [type]          [description]
	 */
	private function deleteVersion($version)
	{
		$sql = sprintf('DELETE FROM schema_migration WHERE version = \'%s\'', $version);
		$this->connection->execute($sql);
	}

	/**
	 * [createIfNotExists description]
	 * @return [type] [description]
	 */
	private function createIfNotExists()
	{
		$table = new Table('schema_migration');
		if($this->info->isTableExists($table)) {
			return;
		}
		$table
			->addColumn(new IntegerColumn('version', 'biginteger'))
			->addColumn(new DateTimeColumn('created_at', 'timestamp', array('nullable' => false, 'default' => 'now()')));		

		$primaryKey = new PrimaryKey(array('version'));
		$primaryKey->setTable($table);
		$this->manipulation->createTable($table, array($primaryKey));		
	}	
}
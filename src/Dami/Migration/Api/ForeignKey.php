<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Constraint\ForeignKey as RentgenForeignKey;
use Rentgen\Database\Table as RentgenTable;

class ForeignKey extends RentgenForeignKey
{	
	public function foreignKey($tableName, $columnNames)
	{
		$this->setTable(new RentgenTable($tableName));
		$this->setColumns($columnNames);
		return $this;
	}

	public function reference($tableName, $columnNames)
	{
		$this->setReferencedTable(new RentgenTable($tableName));
		$this->setReferencedColumns($columnNames);
		return $this;
	}
}
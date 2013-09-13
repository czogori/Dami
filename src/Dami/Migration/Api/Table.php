<?php

namespace Dami\Migration\Api;

use Rentgen\Database\Table as RentgenTable;
use Rentgen\Database\Column\StringColumn;
use Rentgen\Database\Column\IntegerColumn;
use Rentgen\Database\Column\DateTimeColumn;
use Rentgen\Database\Column\BooleanColumn;
use Rentgen\Database\Column\TextColumn;

class Table extends RentgenTable
{	
	public function addStringColumn($name, array $options = array())
	{
		$this->columns[] = new StringColumn($name, $options);
		return $this;	
	}

	public function addIntegerColumn($name, array $options = array())
	{
		$this->columns[] = new IntegerColumn($name, $options);
		return $this;	
	}

	public function addTextColumn($name, array $options = array())
	{
		$this->columns[] = new TextColumn($name, $options);
		return $this;	
	}

	public function addBooleanColumn($name, array $options = array())
	{
		$this->columns[] = new BooleanColumn($name, $options);
		return $this;	
	}

	public function addTimestamps()
	{
		$this->columns[] = new DateTimeColumn('created_at', array('not_null' => true));
		$this->columns[] = new DateTimeColumn('updated_at', array('not_null' => true));
		return $this;
	}
}
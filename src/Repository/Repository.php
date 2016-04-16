<?php

namespace Repository;

abstract class Repository extends \SQLite3
{
	protected $tableName;

	public function __construct()
	{
		$this->open('test.db');
	}

	public function select($params = array())
	{
		$sql = <<<EOF
      SELECT * from $this->tableName
EOF;

		$where = array();
		foreach ($params as $field => $value) {
			$where[] = " {$field} = '{$value}' ";
		}
		if (count($params)) {
			$sql .= ' WHERE ' . implode(' AND ', $where);
		}

		$ret = $this->query($sql);
		return $ret->fetchArray(SQLITE3_ASSOC);
	}

	abstract public function recreate();
}

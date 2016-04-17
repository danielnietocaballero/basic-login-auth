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

	public function insert($params = array())
	{
		$sql = <<<EOF
      INSERT INTO $this->tableName VALUES (null,
EOF;
		$values = array();
		foreach ($params as $value) {
			$values[] = " '{$value}' ";
		}
		if (count($params)) {
			$sql .= implode(',', $values) . ');';
		}

		try {
			$ret = $this->query($sql);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		if (!$ret) {
			throw new \Exception('Cannot insert values');
		}
	}

	public function update($id, $params = array())
	{
		$sql = <<<EOF
      UPDATE $this->tableName SET 
EOF;
		$values = array();
		foreach ($params as $field => $value) {
			$values[] = " {$field} = '{$value}' ";
		}

		$sql .= implode(' , ', $values);
		$sql .= ' WHERE id = ' . $id;

		try {
			$ret = $this->query($sql);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}

		if (!$ret) {
			throw new \Exception('Cannot update values');
		}
	}

	abstract public function recreate();
}

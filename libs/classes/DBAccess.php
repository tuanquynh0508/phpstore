<?php

namespace libs\classes;

use libs\classes\HttpException;

class DBAccess extends \mysqli
{

	/**
	 * {@inheritdoc}
	 * @throws HttpException
	 */
	public function __construct()
	{
		@parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
		if ($this->connect_errno) {
			$message = "Failed to connect to MySQL: (" . $this->connect_errno . ") " . $this->connect_error;
			throw new HttpException($message, 500);
		}
		$this->query("SET NAMES UTF-8");
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function __destruct() {
		$this->close();
	}
	
	/**
	 * 
	 * @param type $tableName
	 * @param type $id
	 * @return boolean
	 * @throws HttpException
	 */
	public function deleteById($tableName, $id)
	{
		$value = false;
		$sql = "DELETE FROM $tableName WHERE id=$id";
		if ($result = $this->query($sql)) {
			$affectedRows = $this->affected_rows;
			if($affectedRows > 0) {
				$value = true;
			}
		} else {
			throw new HttpException($this->error, 500);
		}
		return $value;
	}

	public function findOneById($tableName, $id)
	{
		$record = NULL;
		$sql = "SELECT * FROM $tableName WHERE id=$id";
		if ($result = $this->query($sql)) {
			$record = $result->fetch_object();
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		return $record;
	}

	public function scalarBySQL($sql)
	{
		$value = NULL;
		if ($result = $this->query($sql)) {
			$record = $result->fetch_row();
			if(!empty($record[0])) {
				$value = $record[0];
			}
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		return $value;
	}

	public function findAllBySql($sql)
	{
		$list = array();
		if ($result = $this->query($sql)) {
			while($obj = $result->fetch_object()){
				$list[] = $obj;
			}
			$result->close();
		} else {
			throw new HttpException($this->error, 500);
		}
		return $list;
	}

	public function save($tableName, $attributes, $pkName = null)
	{
		$record = NULL;
		$isAddNew = (null === $pkName)?true:false;

		if($isAddNew) {
			$sql = $this->buildInsertSql($tableName, $attributes);
		} else {
			$sql = $this->buildUpdateSql($tableName, $attributes, $pkName);
		}

		try {
			//Start transaction
			$this->autocommit(FALSE);

			if ($this->query($sql)) {
				//$affectedRows = $this->affected_rows;
				if($isAddNew) {
					$insertId = $this->insert_id;
				} else {
					$insertId = $attributes[$pkName];
				}
				$record = $this->findOneById($tableName, $insertId);
			} else {
				throw new HttpException($this->error, 500);
			}

			//End transaction
			$this->commit();
			$this->autocommit(TRUE);

			return $record;
		} catch (Exception $e) {
			throw new HttpException($e->getMessage(), 500);
			$this->rollback();
			$this->autocommit(TRUE);
		}
	}

	public function buildInsertSql($tableName, $attributes)
	{
		$fields = array_keys($attributes);
		$values = array();

		foreach ($attributes as $value) {
			$values[] = $this->real_escape_string($value);
		}

		$sql = "INSERT INTO $tableName(".implode(",", $fields).") ";
		$sql .= "VALUES ('".implode("','", $values)."')";

		return $sql;
	}

	public function buildUpdateSql($tableName, $attributes, $pkName = null)
	{
		$fields = array();

		foreach ($attributes as $key => $value) {
			if($key !== $pkName) {
				$fields[] = $key."=".(($value !== '')?"'".$this->real_escape_string($value)."'":'NULL');
			}
		}

		$sql = "UPDATE $tableName SET ".implode(', ', $fields)." WHERE $pkName={$attributes[$pkName]}";

		return $sql;
	}

}

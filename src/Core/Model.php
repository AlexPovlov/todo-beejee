<?php

namespace App\Core;

use App\Lib\Db;

/**
 * Абстрактная модель для настледования
 * @var Db $db
 */

abstract class Model
{

	protected $db;

	protected $prepare_data = [];

	protected $where = [];

	protected $order_by = [];

	public function __construct()
	{
		$this->db = new Db;
	}

	private function from()
	{
		return strtolower((new \ReflectionClass($this))->getShortName());
	}

	protected function select($select = "*")
	{
		if (is_array($select)) {
			$select = implode(", ", $select);
		}

		return $select;
	}

	public function get($select = "*")
	{
		return $this->db->GetAllSqlRequest(
			"SELECT {$this->select($select)} FROM `{$this->from()}` {$this->whereStr()}",
			$this->prepare_data
		);
	}

	public function first($select = "*")
	{
		return $this->db->GetOneSqlRequest(
			"SELECT {$this->select($select)} FROM `{$this->from()}` {$this->whereStr()} LIMIT 1",
			$this->prepare_data
		);
	}

	public function find($id, $select = "*")
	{
		return $this->db->GetOneSqlRequest(
			"SELECT {$this->select($select)} FROM `{$this->from()}` WHERE `id` = :id",
			["id" => $id]
		);
	}

	public function orderBy($ordering)
	{
		if ($ordering[1] != "asc") {
			return $this;
		} elseif ($ordering[1] != "desc") {
			return $this;
		}

		$this->order_by = "ORDER BY {$ordering}";

		return $this;
	}

	public function whereStr()
	{
		$str = "";

		if (!empty($this->where)) {

			foreach ($this->where as $key => $value) {
				$str .= "{$key} {$this->toStr($value)}";
			}

			$str = ltrim($str, "AND");
			$str = ltrim($str, "OR");

			return "WHERE $str";
		}

		return $str;
	}

	public function where($data)
	{
		if (isset($this->where["AND"])) {
			$this->where["AND"] = array_merge($this->where["AND"], $data);
		} else {
			$this->where["AND"] = $data;
		}

		return $this;
	}

	public function orWhere($data)
	{
		if (isset($this->where["OR"])) {
			$this->where["OR"] = array_merge($this->where["AND"], $data);
		} else {
			$this->where["OR"] = $data;
		}

		return $this;
	}

	public function delete($id)
	{
		return $this->db->SendSqlRequest(
			"DELETE FROM `{$this->from()}` WHERE `id` = :id",
			["id" => $id]
		);
	}

	public function toStr($data = [])
	{
		$str = "";

		if (count($data) == 3) {
			$str = $data[0] . " " . $data[1] . " :" . $data[0];
			$this->prepare_data = array_merge($this->prepare_data, [$data[0] => $data[2]]);
		} elseif (count($data) == 2) {
			$str = $data[0] . " = :" . $data[0];
			$this->prepare_data = array_merge($this->prepare_data, [$data[0] => $data[1]]);
		}

		return $str;
	}

	public function create($data)
	{
		$columns = "";
		$values = "";

		foreach ($data as $key => $value) {
			$columns .= "`{$key}`, ";
			$values .= ":{$key}, ";
			$this->prepare_data = array_merge($this->prepare_data, [$key => $value]);
		}

		$columns = rtrim($columns, ", ");
		$values = rtrim($values, ", ");

		return $this->db->SendSqlRequest(
			"INSERT INTO `{$this->from()}` ({$columns}) VALUES ({$values})",
			$this->prepare_data
		);
	}

	public function update($id, $data)
	{
		$string = "";

		foreach ($data as $key => $value) {
			$string .= $this->toStr([$key, $value]) . ", ";
		}

		$string = rtrim($string, ", ");

		$this->prepare_data = array_merge($this->prepare_data, ["id" => $id]);

		return $this->db->SendSqlRequest(
			"UPDATE `{$this->from()}`
			SET {$string}
			WHERE `id` = :id",
			$this->prepare_data
		);
	}
}
<?php

namespace Glad\Driver\Adapters\Database;

use PDO;
use Glad\Driver\Adapters\Database\Adapter;
use Glad\Injector;

class PDOAdapter extends Adapter {

	protected $table;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
		$this->constants = Injector::get('Constants');
		$this->table = $this->constants->table;
		$this->checkPdoDriver(false, $exception = true);
	}

	public function insert(array $credentials)
	{
		$fields = implode(array_flip($credentials), ',');

		$bindValues = $this->bindInsertData($credentials);

		$cursor = $this->pdo->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$bindValues})");

		foreach ($credentials as $field => $value) {
			$cursor->bindValue(':'.$field, $value);
		}

		if($cursor->execute()) {
			return $this->pdo->lastInsertId();
		}

		return false;
	}

	public function update(array $where, array $newData, $limit = 1)
	{
		$bindWhere = $this->bindWhere($where);
		$bindData = $this->bindUpdateData($newData);

		$cursor = $this->pdo->prepare("UPDATE {$this->table} SET {$bindData} WHERE({$bindWhere})");

		foreach ($where as $op => $w) {
			foreach($w as $field => $value) {
				$cursor->bindValue(':'.$field, $value, (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
			}
		}

		foreach($newData as $field => $value) {
			$cursor->bindValue(':'.$field, $value,  (is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
		}
		
		return $cursor->execute();
	}

	public function bindWhere($where)
	{
		$_where = '';

		foreach($where as $operator => $w) {

			if(is_array($w)) {
				foreach($w as $field => $value) {
					$_where .= $_where == '' ? $field."=".":{$field}" : " ".strtoupper($operator)." ".$field."=".":{$field}";
				}
			}else{

			}
		}
		return $_where;
	}

	public function bindInsertData(array $data)
	{
		$_data = '';

		foreach ($data as $field => $value) {
			$_data .= $_data == '' ? ':'.$field : ',:'.$field;
		}
	}

	public function bindUpdateData(array $data)
	{
		$_data = '';

		foreach($data as $field => $value) {
			$_data .= $_data == '' ? $field."=".":{$field}" : ",".$field."=".":{$field}";
		}

		return $_data;
	}

	public function getIdentity(array $identity)
	{
		$x = '';
		
		foreach($identity as $field => $value) {
			$x .= ($x == '' ? $field . " = " . "?": ' OR ' . $field . " = " . "?");
		}

		$sql = "SELECT * FROM {$this->table} WHERE {$x} LIMIT 1";

		$cursor = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		
		$i = 1;

		foreach ($identity as $field => $value) {
			$cursor->bindValue($i++, $value, PDO::PARAM_STR);
		}
		
		$cursor->execute();
		
		return $cursor->fetch(\PDO::FETCH_ASSOC);
	}

	public function getIdentityWithId($userId)
	{
		$sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";

		$cursor = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$cursor->bindValue(1, $userId, PDO::PARAM_INT);
		$cursor->execute();

		return $cursor->fetch(\PDO::FETCH_ASSOC);
	}
}
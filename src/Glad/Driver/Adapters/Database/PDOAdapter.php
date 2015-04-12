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

	public function newUser(array $credentials)
	{
		//$this->pdo->beginTransaction();
		//exit(var_dump($credentials));
	}

	public function getIdentity($identity)
	{
		$x = '';

		foreach($identity as $field => $value) {
			$x .= $x == '' ? $field . "=" . "?" : ' OR ' . $field . "=" . "?";
		}

		//exit(var_dump($x));
		$field = key($identity);
		$value = current($identity);
		$sql = "SELECT * FROM {$this->table} WHERE {$x} LIMIT 1";

		$cursor = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);

		$i = 1;
		foreach ($identity as $field => $value) {
			$cursor->bindParam($i, $value, PDO::PARAM_STR);
			$i++;
		}
		//$cursor->bindParam(1, $value, PDO::PARAM_STR);
		$cursor->execute();

		return $cursor->fetch(\PDO::FETCH_ASSOC);
	}

	public function getIdentityWithId($userId)
	{
		$sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";

		$cursor = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
		$cursor->bindParam(1, $userId, PDO::PARAM_INT);
		$cursor->execute();

		return $cursor->fetch(\PDO::FETCH_ASSOC);
	}
}
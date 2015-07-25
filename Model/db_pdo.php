<?php

class DbConnect {

	public $DB_USER = 'a1';
	public $DB_PW = 'A1Password';
	public $databaseName='msgboard';
	public $hostName ='127.0.0.1';
	public $dsn;

	function __construct() {

		$this->dsn = "mysql:dbname=$this->databaseName;host=$this->hostName";
	}

	public function connect() {

		$pdo = new PDO($this->dsn, $this->DB_USER, $this->DB_PW);
		return $pdo;
	}
}


?>

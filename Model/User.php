<?php

require_once('db_pdo.php');

class User {
	//user id
	public $id;
	//username
	public $username;
	//users password
	public $password;

	//store database stuff
	private $pdo;

	function __construct($username, $password) {
		$connection = new DbConnect();
		$this->pdo = $connection->connect();

		$this->username = $username;
		$this->password = $password;




	}

	//returns user if correct or false if failure
	public function attempLogin() {

		$query = "SELECT * from users
				  where username = :username
				  and password =:password	";


		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);

  		var_dump($users);
		echo "attempting login ";

	}

}



?>
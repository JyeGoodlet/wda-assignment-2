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

	//returns true if exists, false if doesnt
	public function attempLogin() {

		$query = "SELECT * from users
				  where username = :username
				  and password =:password
				  limit 1	";


		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);

		
		if (!empty($user)) {
			//get users id
			$this->id = $user->id;
			//echo "true";
			return true;
		}
		else {
			//echo "false";
			return false;
		}

  		
	

	}

}



?>
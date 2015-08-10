<?php

require_once('db_pdo.php');

class User {
	//user id
	public $id;
	//username
	public $username;
	//users password
	public $password;
	//bool isAdmin
	public $isAdmin;
  //bool isBanned
  public $isBanned;




	function __construct($username, $password) {
		$connection = new DbConnect();
		$pdo = $connection->connect();

		$this->username = $username;
		$this->password = $password;
	}

	//returns true if exists, false if doesnt
	public function attemptLogin() {

        $connection = new DbConnect();
        $pdo = $connection->connect();
		    $query = "SELECT * from users
				  where username = :username
				  and password =:password
          and is_banned = 0
				  limit 1	";


		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);

		
		if (!empty($user)) {
			//get users id
			$this->id = $user->id;
			$this->isAdmin = $user->is_admin;
      $this->isBanned = $user->is_banned;			
			return true;
		}
		else {			
			return false;
		}

  		
	

	}

    public function checkUsernameAvailable() {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select username from users
                  where username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user == false) {
            return true;
        }
        else {
            return false;
        }
    }

	public function signup() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
		$query = "insert into users(username, password)
				  values (:username, :password)";
		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();

	}
  
  public function checkIfBanned() {
     $connection = new DbConnect();
     $pdo = $connection->connect();
		 $query = "SELECT is_banned 
                FROM users
                WHERE username = :username";
		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
		$stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_OBJ);
      if ($status == null)
          return false;
    if($status->is_banned == 0)
      return false;
    else
      return true;
  }

}



?>
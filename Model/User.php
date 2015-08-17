<?php

require_once('db_pdo.php');

class User {
	//user id
	public $id;
	//username
	public $username = null;
    //users email
    public $email = null;
	//users password
	public $password;
	//bool isAdmin
	public $isAdmin;
    //bool isBanned
    public $isBanned;

	function __construct($username, $password, $email = '') {
        $username = htmlspecialchars($username);
		$this->username = $username;

        $password = htmlspecialchars($password);
		$this->password = $password;

        $email = htmlspecialchars($email);
        $this->email = $email;
	}

	//returns true if exists, false if doesn't
	public function attemptLogin() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        if ($this->email !== '' ){
            $identify = $this->email;
            $query = "SELECT *
                FROM users
                WHERE email = :identify
                and is_banned = 0 limit 1	";
        }
        else {
            $identify = $this->username;
            $query = "SELECT *
                FROM users
                WHERE username = :identify
                and is_banned = 0 limit 1	";
        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':identify', $identify);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        //check if user exists
        if (!empty($user)) {
            //check if password correct
            if (!password_verify($this->password,$user->password))
                return false;
			//get users id

			$this->id = $user->id;
            $this->username = $user->username;
            $this->email = $user->email;
			$this->isAdmin = $user->is_admin;
            $this->isBanned = $user->is_banned;
			return true;
		}
		else
			return false;
	}



    public static function checkUsernameAvailable($username) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select username from users
                  where username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user == false) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function checkEmailAvailable($email) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select username from users
                  where email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
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
		$query = "insert into users(username, email, password)
				  values (:username, :email, :password)";
		$stmt = $pdo->prepare($query);
		$stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
		$stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
		$stmt->execute();

	}    

    public function deleteUser() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "insert into users(username, email, password)
				  values (:username, :email, :password)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->execute();

    }

    public function checkIfBannedOrDeleted() {

        $connection = new DbConnect();
        $pdo = $connection->connect();

        if (isset($this->email)){
            $identify = $this->email;
            $query = "SELECT is_banned
                FROM users
                WHERE email = :identify";
        }
        else {
            $identify = $this->username;
            $query = "SELECT is_banned
                FROM users
                WHERE username = :identify";

        }

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':identify', $identify);
        $stmt->execute();
        $status = $stmt->fetch(PDO::FETCH_OBJ);
        if ($status == null)
            return true;
        if($status->is_banned == 0)
            return false;
        else
            return true;
    }

}



?>
<?php

require_once('Db_pdo.php');

class Users {

    public function GetAllUsers() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select * from users";
        $stmt = $pdo->prepare($query);       
        $stmt->execute();
        $users = $stmt->fetchAll();

        //var_dump($categories);
        return $users;
    }

    public function GetUserById($id) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * FROM users
                    WHERE id = :id
                    LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user;
    }

    public function getUserByName($username)
    {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * FROM users
                    WHERE username = :username
                    LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        return $user;
    }

    public function UpdateUser($user) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = 'UPDATE users
                    SET is_banned = :is_banned,
                        is_admin = :is_admin
                    WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':is_banned', $user['is_banned']);
        $stmt->bindParam(':is_admin', $user['is_admin']);
        $stmt->bindParam(':id', $user['id']);
        $stmt->execute();
        return $stmt->errorInfo();
    }

    public function DeleteUser($id) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = 'DELETE FROM users
                    WHERE id = :id';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->errorInfo();
    }

}

?>
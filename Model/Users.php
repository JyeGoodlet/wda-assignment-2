<?php

require_once('Db_pdo.php');

class Users {

    public function GetAllUsers() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select * from users WHERE id != 0 ORDER BY username ASC";
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

    public function GetUserDetails($id) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT users.id, users.username, users.is_admin, p.totalPosts, c.totalComments
                  FROM users
                  LEFT JOIN ( SELECT COUNT(user) as totalPosts, user FROM posts GROUP BY user ) p
	              ON users.id = p.user
                  LEFT JOIN ( SELECT COUNT(user) as totalComments, user FROM comments GROUP BY user ) c
	              ON users.id = c.user
	              WHERE users.id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $userDetails = $stmt->fetch(PDO::FETCH_OBJ);
        return $userDetails;
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
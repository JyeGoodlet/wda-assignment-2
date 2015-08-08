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

}

?>
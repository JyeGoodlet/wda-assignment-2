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

}

?>
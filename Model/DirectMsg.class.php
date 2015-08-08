<?php

/**
 * Created by PhpStorm.
 * User: jasonD
 * Date: 8/8/2015
 * Time: 9:05 AM
 */
class DirectMessages
{

    public function getAllMsgs() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * FROM direct_message";
        $stmt = $pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;

    }

}
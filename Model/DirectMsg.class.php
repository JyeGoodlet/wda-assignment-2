<?php

/**  -_-_- AUTHOR: jAsOnD -_-_- */

class DirectMessages

{
    public function getUsersMsgs($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * FROM direct_message WHERE reciever = :id ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }

    public function countUnreadMsgs($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT count(id) AS unread FROM direct_message WHERE reciever = :id AND isRead = 0 GROUP BY isRead";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $count = $stmt->fetch();
        if ($count == null) {
            return 0;
        }
        return $count->unread;
    }
    public function displayMsg($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * FROM direct_message WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $selectedMessage = $stmt->fetch();
        return $selectedMessage;
    }

    //TODO JASON - Create function and query to create user new Message
    public function createMsg() {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        //TODO create statement to INSERT INTO direct_message
        $query = "SELECT * FROM direct_message";
        $stmt = $pdo->prepare($query);
        //TODO bind values
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        //TODO use try catch block for errors
    }

    //TODO JASON - create function and query to delete message
    public function deleteMsg($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        //TODO create statement to DELETE FROM direct_message
        $query = "SELECT * FROM direct_message";
        $stmt = $pdo->prepare($query);
        //TODO bind values
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        //TODO use try catch block for errors
    }
}
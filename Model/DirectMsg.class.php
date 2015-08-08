<?php

/**  -_-_- AUTHOR: jAsOnD -_-_- */

class DirectMessages
{
    /*
    TODO JASON - create function and query to show only individual users messages
    TODO JASON - Create function and query to create user new Message
    TODO JASON - create function and query to delete message

    OPTIONAL EXTRA FUNCTIONALITY

        SORT    - by sender
                - by time
                -- by subject

        FILTER  - by sender
                - by date range
                - by unread
    */
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
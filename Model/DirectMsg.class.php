<?php
/**  Classname: DirectMessages
 *
 * Description:
 * DirectMessages contains static methods to access direct
 * message functionality to be accessed by ModelFacade and User Models
 *
 * -_-_- AUTHOR: jAsOnD -_-_- */

class DirectMessages

{
    // return an array of DIRECT MESSAGE objects that the selected user has received
    public static function getUsersInbox($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.receiver, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as sendername FROM direct_message AS dm, users AS u WHERE receiver = :receiverId  AND dm.isDeleted_receiver = 0 AND dm.sender = u.id ORDER BY dm.timeSent DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':receiverId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }

    // return an array of DIRECT MESSAGE objects that the selected user has sent
    public static function getUserSentbox($senderId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.receiver, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as receiverName FROM direct_message AS dm, users AS u WHERE sender = :senderId  AND dm.isDeleted_sender =0 AND dm.receiver = u.id ORDER BY dm.timeSent DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }

    // return the number of unread received messages as an INT
    public static function getUsersUnreadCount($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT count(id) AS unread FROM direct_message WHERE receiver = :id AND isRead = 0 AND isDeleted_receiver = 0 GROUP BY isRead";
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

    // Return a DIRECT MESSAGE object selected by its msgID
    public static function getDirectMessage($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.receiver, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as sendername FROM direct_message AS dm, users AS u WHERE dm.id = :id AND dm.sender = u.id LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $selectedMessage = $stmt->fetch();
        return $selectedMessage;
    }

    //add a DIRECT MESSAGE record to the database
    public static function createMsg($receiver, $sender, $subject, $message) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "INSERT INTO direct_message (timeSent, sender,receiver,subject,message)"
                    ."VALUES ( now() , :sender, :receiver, :subject, :message)";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':sender', $sender);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        return true;
    }

    //method checks if logged in user is sender or receiver and deletes a message from inbox or sentbox
    public static function deleteMsg($msgId, $userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $ownerQuery = "SELECT id, sender, receiver, isDeleted_sender, isDeleted_receiver FROM direct_message WHERE id = :msgId";
        $stmt = $pdo->prepare($ownerQuery);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $ownerCheck = $stmt->fetch();
        if ($ownerCheck->sender == $userId)
                DirectMessages::deleteMsgSender($msgId);
        if ($ownerCheck->receiver == $userId)
                DirectMessages::deleteMsgReceiver($msgId);
        DirectMessages::removeMsgCheck($ownerCheck->id, $ownerCheck->isDeleted_sender, $ownerCheck->isDeleted_receiver);
        return $stmt->errorInfo();
    }

    //method changes all users msgs sender and receiver to USER DELETED
    public static function removeUserMsgs($userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $ownerQuery = "SELECT id, sender, receiver, isDeleted_sender, isDeleted_receiver FROM direct_message
                      WHERE sender = :userId OR receiver = :userId";
        $stmt = $pdo->prepare($ownerQuery);
        $stmt->bindParam(':userId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $ownerCheck = $stmt->fetchAll();
        foreach ($ownerCheck as $eachMsg) {

            if ($eachMsg->sender == $userId)
                DirectMessages::removeSender($eachMsg->id);
            if ($eachMsg->receiver == $userId)
                DirectMessages::removeReceiver($eachMsg->id);
            DirectMessages::removeMsgCheck($eachMsg->id, $eachMsg->isDeleted_sender, $eachMsg->isDeleted_receiver);
        }
        return $stmt->errorInfo();
    }

    // method deletes msg from senders sentbox
    private static function deleteMsgSender($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_sender = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    // method deletes msg from receivers Inbox
    private static function deleteMsgReceiver($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_receiver = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    // method changes sender to USER DELETED
    private static function removeSender($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET sender = 0, isDeleted_sender = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
        return $stmt->errorInfo();
    }

    //method changes receiver to USER DELETED
    private static function removeReceiver($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET receiver = 0, isDeleted_receiver = 1  WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
        return $stmt->errorInfo();

    }

    //if message has been deleted from both senders sentbox and receivers inbox then message is deleted from database
    private function removeMsgCheck($msgId, $isDeleted_receiver, $isDeleted_sender)
    {
        if (($isDeleted_sender = 1)
            && ($isDeleted_receiver = 1) ) {

            $connection = new DbConnect();
            $pdo = $connection->connect();
            $deletion = "DELETE FROM direct_message WHERE id = :msgId";
            $stmt = $pdo->prepare($deletion);
            $stmt->bindParam('msgId', $msgId);
            $stmt->execute();
            return $stmt->errorInfo();
        }
        else
            return false;
    }

    //Update the database to mark a DIRECT MESSAGE as read
    public static function markAsRead($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isRead = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }
}
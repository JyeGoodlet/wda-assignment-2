<?php
/**  Classname: DirectMessages
 *
 * Description: DirectMessages contains static methods to access direct
 * message functionality to be accessed by ModelFacade and User Models
 *
 * -_-_- AUTHOR: jAsOnD -_-_- */

class DirectMessages

{

    public static function getUsersInbox($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.reciever, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as sendername FROM direct_message AS dm, users AS u WHERE reciever = :receiverId  AND dm.isDeleted_receiver = 0 AND dm.sender = u.id ORDER BY dm.timeSent DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':receiverId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }

    public static function getUserSentbox($senderId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.reciever, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as receiverName FROM direct_message AS dm, users AS u WHERE sender = :senderId  AND dm.isDeleted_sender =0 AND dm.reciever = u.id ORDER BY dm.timeSent DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }


    public static function getUsersUnreadCount($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT count(id) AS unread FROM direct_message WHERE reciever = :id AND isRead = 0 AND isDeleted_receiver = 0 GROUP BY isRead";
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

    public static function getDirectMessage($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.reciever, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as sendername FROM direct_message AS dm, users AS u WHERE dm.id = :id AND dm.sender = u.id LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $selectedMessage = $stmt->fetch();
        return $selectedMessage;
    }

    public static function createMsg($receiver, $sender, $subject, $message) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "INSERT INTO direct_message (timeSent, sender,reciever,subject,message)"
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
        $ownerQuery = "SELECT id, sender, reciever, isDeleted_sender, isDeleted_receiver FROM direct_message WHERE id = :msgId";
        $stmt = $pdo->prepare($ownerQuery);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $ownerCheck = $stmt->fetch();
        if ($ownerCheck->sender == $userId)
                DirectMessages::deleteMsgSender($msgId);
        if ($ownerCheck->reciever == $userId)
                DirectMessages::deleteMsgReceiver($msgId);
        DirectMessages::removeMsgCheck($ownerCheck->id, $ownerCheck->isDeleted_sender, $ownerCheck->isDeleted_receiver);
        return $stmt->errorInfo();
    }

    //method changes all users msgs sender and receiver to USER DELETED
    public static function removeUserMsgs($userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $ownerQuery = "SELECT id, sender, reciever, isDeleted_sender, isDeleted_receiver FROM direct_message
                      WHERE sender = :userId OR reciever = :userId";
        $stmt = $pdo->prepare($ownerQuery);
        $stmt->bindParam(':userId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $ownerCheck = $stmt->fetchAll();
        foreach ($ownerCheck as $eachMsg) {

            if ($eachMsg->sender == $userId)
                DirectMessages::removeSender($eachMsg->id);
            if ($eachMsg->reciever == $userId)
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
        $query = "UPDATE direct_message SET reciever = 0, isDeleted_receiver = 1  WHERE id = :msgId";
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

    public static function markAsRead($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isRead = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }
}
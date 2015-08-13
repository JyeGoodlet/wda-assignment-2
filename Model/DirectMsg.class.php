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

    public static function deleteMsg($msgId, $userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $ownerQuery = "SELECT id, sender, reciever, isDeleted_sender, isDeleted_receiver FROM direct_message WHERE id = :msgId";
        $stmt = $pdo->prepare($ownerQuery);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $ownerCheck = $stmt->fetch();

        if ($ownerCheck->sender == $userId) {
            if ($ownerCheck->isDeleted_receiver == 1)
                DirectMessages::deleteMsgFull($msgId);
            else
                DirectMessages::deleteMsgSender($msgId);
        }

        if ($ownerCheck->reciever == $userId) {
            if ($ownerCheck->isDeleted_sender == 1)
                DirectMessages::deleteMsgFull($msgId);
            else
                DirectMessages::deleteMsgReceiver($msgId);
        }
        return true;
    }

    private static function deleteMsgSender($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_sender = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    private static function deleteMsgReceiver($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_receiver = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    public static function deleteAllUserMsgs($userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $ownerQuery = "SELECT id, sender, reciever, isDeleted_sender, isDeleted_receiver FROM direct_message
                      WHERE sender = :userId AND reciever = :userId";
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
        }
        return true;
    }


    private static function removeSender($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET sender = 0 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    private static function removeReceiver($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET receiver = 0 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    private function deleteMsgFull($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "DELETE FROM direct_message WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('msgId', $msgId);
        $stmt->execute();
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
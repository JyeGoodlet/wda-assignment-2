<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

class DirectMessages

{

    public function getMsgInbox($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.reciever, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as sendername FROM direct_message AS dm, users AS u WHERE reciever = :receiverId  AND dm.isDeleted_receiver = 0 AND dm.sender = u.id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':receiverId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }
    public function getMsgSent($senderId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT dm.id, dm.sender, dm.reciever, dm.isRead, dm.timeSent, dm.subject, dm.message, u.username as receiverName FROM direct_message AS dm, users AS u WHERE sender = :senderId  AND dm.isDeleted_sender =0 AND dm.reciever = u.id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $allMessages = $stmt->fetchAll();
        return $allMessages;
    }


    public function countUnreadMsgs($userId) {
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
    public function displayMsg($msgId) {
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

    public function createMsg($receiver, $sender, $subject, $message) {
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
        //TODO use try catch block for errors
    }

    public function deleteMsg($msgId, $userId) {

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
                $this->deleteMsgFull($msgId);
            else
                $this->deleteMsgSender($msgId);
        }

        //Bug Fixed elseif to else so you can delete messages sent to yourself
        if ($ownerCheck->reciever == $userId) {
            if ($ownerCheck->isDeleted_sender == 1)
                $this->deleteMsgFull($msgId);
            else
                $this->deleteMsgReceiver($msgId);
        }

    }

    private function deleteMsgSender($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_sender = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
    }

    private function deleteMsgReceiver($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isDeleted_receiver = 1 WHERE id = :msgId";
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


    public function markAsRead($msgId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE direct_message SET isRead = 1 WHERE id = :msgId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':msgId', $msgId);
        $stmt->execute();
        //TODO use try catch block for errors
    }
}
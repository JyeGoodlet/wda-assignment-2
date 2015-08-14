<?php

/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/5/2015
 * Time: 9:14 PM
 */
require_once "ThreadModel.php";

class ThreadsModel
{

    public $threads;


    public function getThreads($subCategoryId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //monster query - i might need to refactor this
        $query = "select threads.id, threads.date, threads.title, users.id as posterId, users.username, COALESCE(comment.commentcount,0) as commentcount,
                    lastpost.id as lastUserId, lastpost.username as lastuser, lastpost.date as lastdate, threads.last_activity_timestamp, threads.closed from threads
                    left join users on threads.user = users.id
                    /* get comment count */
                    left join (
                    select post, count(*) as commentcount from comments
                    group by post
                    ) as comment on comment.post = threads.id
                    left join (
          select users.id, post, username, date from comments
                    join users on comments.user = users.id
			 group by post
                    order by date desc
                    ) as lastpost on lastpost.post = threads.id
                    where subcategory = :id
                    order by last_activity_timestamp desc";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $subCategoryId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $threads = $stmt->fetchAll();
        return $threads;

    }

	public function getThread($id) {
		$connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select threads.id, threads.date, threads.title, threads.subcategory as subCatId, threads.user, threads.content, users.username, subcategories.subcategory FROM threads
                  left join users on users.id = threads.user
                  left join subcategories on subcategories.id = threads.subcategory
                    WHERE threads.id = :id
                    LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $thread = $stmt->fetch();
        return $thread;
	}

    public function getThreadComments($threadId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select comments.id as commentId, comments.date, comments.comment, users.id as UserId, users.username from comments
                  join users on users.id = comments.user
                  where post = :post";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post', $threadId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $comments = $stmt->fetchAll();

        return $comments;
    }



    public function addComment($threadId, $comment, $userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "insert into comments (date,  post, comment,  user)
                    values (now(), :post, :comment, :user)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post', $threadId);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':user', $userId);


        //$stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function checkThreadClosed($threadId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select closed from threads
                  where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $threadId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $value =  $stmt->fetch();
        //var_dump($value->closed);
        return $value->closed;

    }

    public function AdminCloseThread($id, $closingMessage, $adminId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        //insert comment
        $query = "insert into comments (date,  post, comment,  user)
                    values (now(), :post, :comment, :user)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post', $id);
        $stmt->bindParam(':comment', $closingMessage);
        $stmt->bindParam(':user', $adminId);
        $stmt->execute();

        //update to closed
        $query = "UPDATE threads
                  set closed  = true
                  where id=:id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();


        //commit
        $pdo->commit();

    }

    public function AdminDeleteComment($id) {
        $comment = "[Comment removed by " . ModelFacade::getLoggedInUser()->username ."]";
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "UPDATE comments
                    SET comment = :comment
                    WHERE id = :id";
        $stmt= $pdo->prepare($query);
        $stmt->bindParam(":comment", $comment);
        $stmt->bindParam(":id", $id);
        $stmt->execute();





        return $stmt->errorInfo();
    }


    /* when a user adds a comment to a post it will alter the posts last activity. that
    way active posts stay at the top of the forum */
    public function updateLastActivity($threadId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "update threads
                  set last_activity_timestamp = now()
                  where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $threadId);
        $stmt->execute();
    }

    public static function userProfileThreads($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select threads.subcategory, subcategories.subcategory as subcat, subcategories.category as cat, threads.id, threads.date, threads.title, users.id as posterId, users.username, COALESCE(comment.commentcount,0) as commentcount,
                    threads.last_activity_timestamp, threads.closed from threads
                    left join users on threads.user = users.id
                    /* get comment count */
                    left join (
                    select post, count(*) as commentcount from comments
                    group by post
                    ) as comment on comment.post = threads.id

                    LEFT JOIN (
          select subcat.subcategory,  subcat.id, cat.category
          FROM subcategories subcat, categories cat
          WHERE subcat.category_id = cat.id
                    ) as subcategories on subcategories.id = threads.subcategory
                    WHERE users.id = :userId
                    order by last_activity_timestamp desc";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $usersThreads = $stmt->fetchAll();
        return $usersThreads;
    }


    public static function deleteAllUsersThreads($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE threads SET user = 0
                    WHERE user = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }


    public function deleteAllUsersComments($userId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "UPDATE comments SET user = 0
                    WHERE user = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
    }

}
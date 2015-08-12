<?php

/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/5/2015
 * Time: 9:14 PM
 */
require_once "Post.php";

class Posts
{

    public $posts;


    public function getPosts($subCategoryId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //monster query - i might need to refactor this
        $query = "select posts.id, posts.date, posts.title, users.id as posterId, users.username, COALESCE(comment.commentcount,0) as commentcount,
                    lastpost.id as lastUserId, lastpost.username as lastuser, lastpost.date as lastdate, posts.last_activity_timestamp from posts
                    left join users on posts.user = users.id
                    /* get comment count */
                    left join (
                    select post, count(*) as commentcount from comments
                    group by post
                    ) as comment on comment.post = posts.id
                    left join (
          select users.id, post, username, date from comments
                    join users on comments.user = users.id
			 group by post
                    order by date desc
                    ) as lastpost on lastpost.post = posts.id
                    where subcategory = :id
                    order by last_activity_timestamp desc";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $subCategoryId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $posts = $stmt->fetchAll();
        return $posts;

    }

	public function getPost($id) {
		$connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select posts.id, posts.date, posts.title, posts.subcategory as subCatId, posts.user, posts.content, users.username, subcategories.subcategory FROM posts
                  left join users on users.id = posts.user
                  left join subcategories on subcategories.id = posts.subcategory
                    WHERE posts.id = :id
                    LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $post = $stmt->fetch();
        return $post;
	}

    public function getPostComment($postId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select comments.id as commentId, comments.date, comments.comment, users.id as UserId, users.username from comments
                  join users on users.id = comments.user
                  where post = :post";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post', $postId);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $comments = $stmt->fetchAll();

        return $comments;
    }



    public function addComment($postId, $comment, $userId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "insert into comments (date,  post, comment,  user)
                    values (now(), :post, :comment, :user)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':post', $postId);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':user', $userId);


        //$stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $pdo->lastInsertId();
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
    public function updateLastActivity($postId) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "update posts
                  set last_activity_timestamp = now()
                  where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $postId);
        $stmt->execute();
    }


}
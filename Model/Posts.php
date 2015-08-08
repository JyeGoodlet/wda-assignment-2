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
                    ) as comment on comment.post = posts.id
                    left join (
                    select users.id, post, username, date from comments
                    join users on comments.user = users.id
                    order by date desc
                    limit 1) as lastpost on lastpost.post = posts.id
                    where subcategory = :id
                    order by last_activity_timestamp";
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
        $query = "select * FROM posts
                  left join users on users.id = posts.user
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
        $query = "select * from comments
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


}
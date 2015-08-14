<?php

/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/5/2015
 * Time: 8:24 PM
 */
class ThreadModel
{

    public $id;

    public $date;

    public $title;

    public $content;

    //hold the subcateogry details
    public $subcategory;

    //hold the user
    public $user;

    //this records the last time a comment when posted. The first value should be the timestamp when the thread was
    //created
    public $lastActivity;





    public function __construct($title, $content, $subcategory, $user) {
        $this->title = $title;
        $this->content = $content;
        $this->subcategory = $subcategory;
        $this->user = $user;
    }

    public function addThread() {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "insert into threads (date, title, content, user, subcategory)
                    values (now(), :title, :content, :user, :subcategory)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':subcategory', $this->subcategory);

        //$stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $pdo->lastInsertId();


    }


}
<?php

/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/4/2015
 * Time: 11:29 PM
 */
Class Subcategory
{
    public $id;
    public $category_id;
    public $subcategory;


    public function __construct()
    {

    }


    public function getSubcategory($id)
    {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "select * from subcategories
                  where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Subcategory');
        $stmt->execute();
        $subcategory = $stmt->fetch();


        return $subcategory;
        //return $this;
    }
}


?>


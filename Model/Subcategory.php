<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/4/2015
 * Time: 11:29 PM
 */

Class Subcategory{
    public $id;
    public $category_id;
    public $subcategory;

    public function __construct() {

    }

    public function getSubcategory( $id) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "select * from subcategories
                  where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Subcategory');
        $stmt->execute();
        $subcategory = $stmt->fetch();
        $this->id = $subcategory->id;
        $this->category_id = $subcategory->category_id;
        $this->subcategory = $subcategory->subcategory;

        return $subcategory;
    }
}



?>


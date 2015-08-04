<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/4/2015
 * Time: 11:01 PM
 */
require_once "Subcategory.php";
class Categories {

    //hold our category items
    public $categories;

    public function getAllCategoriesWithSubcategories()
    {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * from categories	";


        $stmt = $pdo->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Category');
        $stmt->execute();
        $categories = $stmt->fetchAll();
        foreach($categories as $category) {
            $category->subcategories = $this->getSubCategories($category->id);
        }


        //var_dump($categories);
        return $categories;



    }


    private function getSubcategories($categoryId) {

        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * from subcategories
                  where category_id = :categoryId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('categoryId', $categoryId);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Subcategory');
        $stmt->execute();
        $subcategories = $stmt->fetchAll();
        return $subcategories;


    }

}


?>
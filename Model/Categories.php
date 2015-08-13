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

    public function getCategory($id) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * from categories
                    WHERE id = :categoryId
                    LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('categoryId', $id);
        $stmt->execute();
        $category = $stmt->fetch();
        return $category;       
    
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

    private function checkIfCategoryExists($categoryName) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * from categories
                  where category = :category";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('category', $categoryName);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    private function checkIfSubcategoryExists($parentId, $subcategoryName) {
        $connection = new DbConnect();
        $pdo = $connection->connect();
        $query = "SELECT * from subcategories
                    WHERE subcategory = :subcategory
                    AND category_id = :parentId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam('subcategory', $subcategoryName);
        $stmt->bindParam('parentId', $parentId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function AddCategory($categoryName) {
        if(!$this->checkIfCategoryExists($categoryName)) {
            $connection = new DbConnect();
            $pdo = $connection->connect();
            $query = "INSERT INTO categories(category)
                        VALUES(:categoryName)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":categoryName", $categoryName);
            $stmt->execute();
            return $stmt->errorInfo();
        }
        else {
            return null;
        }

    }

    public function EditCategory($id, $categoryName) {
        if(!$this->checkIfCategoryExists($categoryName)) {
            $connection = new DbConnect();
            $pdo = $connection->connect();
            $query = "UPDATE categories
                        SET category = :category
                        WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":category", $categoryName);
            $stmt->execute();
            return $stmt->errorInfo();
        }
        else {
            return null;
        }

    }

    public function addSubcategory($parentId, $subcatgeoryName) {
        if(!$this->checkIfSubcategoryExists($parentId, $subcatgeoryName)) {
            $connection = new DbConnect();
            $pdo = $connection->connect();
            $query = "INSERT INTO subcategories(category_id, subcategory)
                        VALUES(:categoryId, :subcategoryName)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":categoryId", $parentId);
            $stmt->bindParam(":subcategoryName", $subcatgeoryName);
            $stmt->execute();
            return $stmt->errorInfo();
        }
        else {
            return null;
        }
    }

}


?>
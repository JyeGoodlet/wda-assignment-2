<?php

require "User.php";

require "Category.php";
require "Subcategory.php";
require "Categories.php";


Class ModelFacade {
    

	public static function login($username, $password) {
		//
		$user = new User($username, $password);
		
		if ($user->attempLogin()) {
			session_start();
			$_SESSION["user"] = $user;

		}

		
		

	}

    public static function getLoggedInUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION["user"] ;
    }

	public static function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		session_destroy();
	}

	public static function signup($username, $password) {
		$user = new User($username, $password);
		$user->signup();

	}

	public static function checkLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		if (isset($_SESSION['user'])) {
			return true;
		}
		else {
			return false;
		}
	}

	public static function redirectUnauthorises() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		if (!isset($_SESSION['user'])) {
			header("Location: /login.php");
		}

	}

    public static function checkUsernameAvaiable($username) {
        $user = new User($username, "");
        $avaiable = $user->checkUsernameAvailable();
        return $avaiable;

    }


	public static function getAllCategoriesWithSubcategories() {
		$categories = new Categories();
		$categories = $categories->getAllCategoriesWithSubcategories();
		return $categories;

	}

	public static function getSubCategory($id) {
		$subcategory = new Subcategory();
		$subcategory->getSubcategory($id);
		return $subcategory;
	}





}




?>
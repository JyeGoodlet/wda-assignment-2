<?php

require "User.php";
require "Users.php";

require "Category.php";
require "Subcategory.php";
require "Categories.php";
require "Posts.php";
require_once "Post.php";

class ModelFacade {
    

	public static function login($username, $password) {
		//
		$user = new User($username, $password);
		
		if ($user->attemptLogin()) {
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

	public static function redirectUnauthorised() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		if (!isset($_SESSION['user'])) {
			header("Location: /Login.php");
		}

	}

    public static function redirectUnauthorisedNotAdmin() {
        ModelFacade::redirectUnauthorised();

        // Check if admin - if not, boot them back to index
        if(!ModelFacade::getLoggedInUser()->isAdmin) {
            header("Location: /Index.php");
        }

	}

    public static function checkUsernameAvaiable($username) {
        $user = new User($username, "");
        $avaiable = $user->checkUsernameAvailable();
        return $avaiable;

    }

	//get categories with subcategories
	public static function getAllCategoriesWithSubcategories() {
		$categories = new Categories();
		$categories = $categories->getAllCategoriesWithSubcategories();
		return $categories;

	}

	//get subcategory by its id
	public static function getSubCategory($id) {
		$subcategory = new Subcategory();
		$subcategory = $subcategory->getSubcategory($id);
		return $subcategory;
	}

	//get a subcategories posts
	public static function getPosts($subcategoryId) {

		$posts = new Posts();
		$posts = $posts->getPosts($subcategoryId);
		return $posts;

	}
  
  public static function getPost($id) {
    $posts = new Posts();
    $posts = $posts->getPost($id);
    return $posts;
  }

  public static function GetAllUsers() {
      $users = new Users();
      return $users->GetAllUsers();
  }

	public static function insertPost($title, $content, $subcategory, $user) {
		$post = new Post($title, $content, $subcategory,  $user);
		return $post->addPost();



	}




}




?>
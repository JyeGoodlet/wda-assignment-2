<?php

require "User.php";
require "Users.php";

require "Category.php";
require "Subcategory.php";
require "Categories.php";
require "Posts.php";

require_once "Post.php";
require "DirectMsg.class.php";

class ModelFacade {
    
    public static function GetAppTitle() {
        return "ThreadIT";
    }

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

    public static function getUserDetails($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $users = new Users();
        $details = $users->GetUserDetails($userId);
        return $details ;
        //TODO create getUserDetails in User.php
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
  
  public static function checkIfBanned($user) {
    $userObj = new User($user, '');
    return $userObj->checkIfBanned($user);
  }

    public static function kickIfBanned() {
        if (ModelFacade::checkLoggedIn()) {
            $userObj = ModelFacade::getLoggedInUser();
            if ($userObj->checkIfBanned()) {
                ModelFacade::logout();
                header("Location: /Login.php");
            }
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
  
    public static function getMsgInbox() {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messages = new DirectMessages();
        $messages = $messages->getMsgInbox($userId);
        return $messages;

    }

    public static function getMsgSent() {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messages = new DirectMessages();
        $messages = $messages->getMsgSent($userId);
        return $messages;

    }

    public static function getMsg($msgId) {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messages = new DirectMessages();
        $messages = $messages->displayMsg($msgId);
        if (($messages->reciever != $userId)&&($messages->sender != $userId))
            header("Location: /DirectMsgInbox.php");
        return $messages;

    }

    public static function countUnreadMsgs() {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messages = new DirectMessages();
        $messages = $messages->countUnreadMsgs($userId);
        return $messages;

    }

    public static function createMsg($receiver, $subject, $message) {
        $sender = ModelFacade::getLoggedInUser()->id;
        $newMessage = new DirectMessages();

        $newMessage = $newMessage->createMsg($receiver,$sender,$subject, $message);
        return true;

    }

    public static function deleteMsg($msgId) {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messages = new DirectMessages();
        $messages->deleteMsg($msgId, $userId);
        //TODO add visual feedback that message is delete
        return true;

    }

    public static function markMsgRead($msgId) {
        $messages = new DirectMessages();
        $messages->markAsRead($msgId);


    }

  public static function getPost($id) {
    $posts = new Posts();
    $posts = $posts->getPost($id);
    return $posts;
  }


  public static function getPostComments($postId) {

	return (new Posts())->getPostComment($postId);


}


  //Get all registered users from the database
  public static function GetAllUsers() {
      $users = new Users();
      return $users->GetAllUsers();
  }

  // Get a user from the database by Id
  public static function GetUserById($id) {
      $users = new Users();
      $user = $users->GetUserById($id);
      return $user;
  }

    // Get a user from the database by Name
    public static function getUserByName($name) {
        $users = new Users();
        $user = $users->getUserByName($name);
        return $user;
    }
	public static function insertPost($title, $content, $subcategory, $user) {
		$post = new Post($title, $content, $subcategory,  $user);
		return $post->addPost();
	}

    public static function DeleteUser($id) {
        $users = new Users();
        return $users->DeleteUser($id);
    }

    public static function addComment($postId, $comment, $userId) {

        $post = new Posts();
        $post->addComment($postId, $comment, $userId);
        $post->updateLastActivity($postId);
    }

    public static function AdminDeleteComment($id) {
        $posts = new Posts();
        return $posts->AdminDeleteComment($id);
    }





	public static function friendlyDate($date) {
		if ($date == null) {
			return "";
		}
		$date = date_create($date);
		return date_format($date, 'l jS \of F Y h:i A');
	}

    public static function UpdateUser($user) {
        $users = new Users();
        return $users->UpdateUser($user);
    }



}




?>
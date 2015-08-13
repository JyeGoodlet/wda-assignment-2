<?php

require "User.php";
require "Users.php";

require "Category.php";
require "Subcategory.php";
require "Categories.php";
require "ThreadsModel.php";

require_once "ThreadModel.php";
require "DirectMsg.class.php";

class ModelFacade {
    
    public static function GetAppTitle() {
        return "ThreadIT";
    }

	public static function login($identify, $password) {

        //if email is provided
        if (filter_var($identify, FILTER_VALIDATE_EMAIL))
            $user = new User("", $password, $identify);

        //if username is provided
        else
            $user = new User($identify, $password);

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
    }


    public static function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
		session_destroy();
	}

	public static function signup($username, $password, $email) {
		$user = new User($username, $password, $email);
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
  
  public static function checkIfBanned($identify) {
      if (strpos($identify,"@" !== false))
          $userObj = new User('', '', $identify);
      else
        $userObj = new User($identify , '');
    return $userObj->checkIfBanned();
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
            exit();
		}


	}

    public static function redirectUnauthorisedNotAdmin() {
        ModelFacade::redirectUnauthorised();

        // Check if admin - if not, boot them back to index
        if(!ModelFacade::getLoggedInUser()->isAdmin) {
            header("Location: /Index.php");
            exit();
        }

	}

    public static function checkUsernameAvaiable($username) {
        $avaiable = User::checkUsernameAvailable($username);
        return $avaiable;

    }

    public static function checkEmailAvaiable($email) {
        $avaiable = User::checkEmailAvailable($email);
        return $avaiable;

    }


    //get categories with subcategories
	public static function getAllCategoriesWithSubcategories() {
		$categories = new Categories();
		$categories = $categories->getAllCategoriesWithSubcategories();
		return $categories;

	}

    //get category name by its id
    public static function getCategory($id) {
        $categories = new Categories();
		return $categories->getCategory($id);		
    }

	//get subcategory by its id
	public static function getSubCategory($id) {
		$subcategory = new Subcategory();
		$subcategory = $subcategory->getSubcategory($id);
		return $subcategory;
	}

	//get a subcategories threads
	public static function getThreads($subcategoryId) {

		$threads = new ThreadsModel();
		$threads = $threads->getThreads($subcategoryId);
		return $threads;

	}
  
    public static function getUsersInbox($userId) {
        $inbox = DirectMessages::getUsersInbox($userId);
        return $inbox;

    }

    public static function getUsersSentbox($userId) {
        $sentbox = DirectMessages::getUserSentbox($userId);
        return $sentbox;

    }

    public static function getUsersThreads($userId) {
        $usersThreads = ThreadsModel::userProfileThreads($userId);
        return $usersThreads;

    }



    public static function getMsg($msgId) {
        $userId = ModelFacade::getLoggedInUser()->id;
        $msg = DirectMessages::getDirectMessage($msgId);
        if (($msg->reciever != $userId)&&($msg->sender != $userId))
            header("Location: /DirectMsgInbox.php");
        return $msg;

    }

    public static function countUnreadMsgs() {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messageCount = DirectMessages::getUsersUnreadCount($userId);
        return $messageCount;

    }

    public static function createMsg($receiver, $subject, $message) {
        $sender = ModelFacade::getLoggedInUser()->id;
        $isCreated = DirectMessages::createMsg($receiver,$sender,$subject, $message);
        return $isCreated;

    }



    public static function deleteMsg($msgId) {
        $userId = ModelFacade::getLoggedInUser()->id;
        $isDeleted = DirectMessages::deleteMsg($msgId, $userId);
        return $isDeleted;

    }

    public static function markMsgRead($msgId) {
        DirectMessages::markAsRead($msgId);
    }

  public static function getThread($id) {
    $Threads = new ThreadsModel();
    $Threads = $Threads->getThread($id);
    return $Threads;
  }


  public static function getThreadComments($threadId) {

	return (new ThreadsModel())->getThreadComments($threadId);


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


	public static function insertThread($title, $content, $subcategory, $user) {
		$thread = new ThreadModel($title, $content, $subcategory,  $user);
		return $thread->addThread();
	}

    public static function DeleteUser($id) {
        $users = new Users();
        return $users->DeleteUser($id);
    }

    public static function addComment($threadId, $comment, $userId) {

        $thread = new ThreadsModel();
        $thread->addComment($threadId, $comment, $userId);
        $thread->updateLastActivity($threadId);
    }

    public static function AdminDeleteComment($id) {
        $threads = new ThreadsModel();
        return $threads->AdminDeleteComment($id);
    }

    public static function closeThread($threadId, $closingMessage, $adminId) {
        $threads = new ThreadsModel();
        $threads->AdminCloseThread($threadId, $closingMessage, $adminId);

    }

    public static function checkThreadClosed($threadId) {
        $thread = new ThreadsModel();
        return $thread->checkThreadClosed($threadId);

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

    public static function AdminAddBoard($categoryName) {
        $categories = new Categories();
        return $categories->AddCategory($categoryName);
    }

    public static function AdminEditBoard($id, $categoryName) {
        $categories = new Categories();
        return $categories->EditCategory($id, $categoryName);
    }

    public static function AdminAddSubcategory($categoryId, $subcategoryName) {
        $categories = new Categories();
        return $categories->AddSubcategory($categoryId, $subcategoryName);       
    }

    public static function AdminEditSubcategory($id, $subcategoryName) {
        $categories = new Categories();
        return $categories->EditSubcategory($id, $subcategoryName);
    }


}




?>
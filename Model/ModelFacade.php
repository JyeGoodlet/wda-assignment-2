<?php

require "User.php";
require "Users.php";

require "Category.php";
require "Subcategory.php";
require "Categories.php";
require "ThreadsModel.php";

require_once "ThreadModel.php";
require "DirectMsg.class.php";

class ModelFacade
{

    public static function GetAppTitle()
    {
        return "ThreadIT";
    }

    //Used to log members into the site
    public static function login($identify, $password)
    {

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

    //Used to get the details of the current logged in user.
    public static function getLoggedInUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION["user"];
    }

    //Gets users details based on user id
    public static function getUserDetails($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $users = new Users();
        $details = $users->GetUserDetails($userId);
        return $details;
    }

    // logs the users out
    public static function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
    }

    //Add a new member to the site
    public static function signup($username, $password, $email)
    {
        $user = new User($username, $password, $email);
        $user->signup();

    }

    //checks if a user is currently logged in
    public static function checkLoggedIn()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            return true;
        } else {
            return false;
        }
    }

    //Check is a user is banned based on id
    public static function checkIfBanned($identify)
    {
        if (filter_var($identify, FILTER_VALIDATE_EMAIL))
            $userObj = new User('', '', $identify);
        else
            $userObj = new User($identify, '');
        return $userObj->checkIfBannedOrDeleted();
    }

    //Logs user out if they are banned or account deleted
    public static function kickIfBannedOrDeleted()
    {
        if (ModelFacade::checkLoggedIn()) {
            $userObj = ModelFacade::getLoggedInUser();
            $test = $userObj->checkIfBannedOrDeleted();
            if ($test) {
                ModelFacade::logout();
                header("Location: /Login.php");
            }
        }
    }

    //Confirm that password entered is correct
    public static function confirmPassword($userId, $password)
    {
        $users = new Users();
        return $users->testUserPassword($userId, $password);
    }

    //Updates Users password
    public static function updatePassword($userId, $password)
    {
        $users = new Users();
        return $users->updatePassword($userId, $password);
    }

    //Used on secure pages to redirect non logged in users.
    public static function redirectUnauthorised()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: /Login.php");
            exit();
        }


    }

    //Redirect a user if they are not an admin
    public static function redirectUnauthorisedNotAdmin()
    {
        ModelFacade::redirectUnauthorised();

        // Check if admin - if not, boot them back to index
        if (!ModelFacade::getLoggedInUser()->isAdmin) {
            header("Location: /Index.php");
            exit();
        }

    }

    //check if a username has already been used.
    public static function checkUsernameAvaiable($username)
    {
        $avaiable = User::checkUsernameAvailable($username);
        return $avaiable;

    }

    //checks if an email has already been used
    public static function checkEmailAvaiable($email)
    {
        $avaiable = User::checkEmailAvailable($email);
        return $avaiable;

    }


    //get categories with subcategories
    public static function getAllCategoriesWithSubcategories()
    {
        $categories = new Categories();
        $categories = $categories->getAllCategoriesWithSubcategories();
        return $categories;

    }

    //get category name by its id
    public static function getCategory($id)
    {
        $categories = new Categories();
        return $categories->getCategory($id);
    }

    //get subcategory by its id
    public static function getSubCategory($id)
    {
        $subcategory = new Subcategory();
        $subcategory = $subcategory->getSubcategory($id);
        return $subcategory;
    }

    //get a subcategories threads
    public static function getThreads($subcategoryId)
    {

        $threads = new ThreadsModel();
        $threads = $threads->getThreads($subcategoryId);
        return $threads;
    }

    //search threads. This search is based on thread title and not thread content or comments
    public static function SearchThreads($search)
    {
        $threads = new ThreadsModel();
        $result = $threads->searchThreads($search);
        return $result;
    }

    //Update Thread Category state offline/online
    public static function UpdateBoardState($categoryId, $isOffline)
    {
        $threads = new ThreadsModel();
        return $threads->UpdateBoardState($categoryId, $isOffline);
    }

    //Update Thread state offline/online
    public static function UpdateSubcategoryState($subcategoryId, $isOffline)
    {
        $threads = new ThreadsModel();
        return $threads->UpdateSubcategoryState($subcategoryId, $isOffline);
    }

    //get users inbox data
    public static function getUsersInbox($userId)
    {
        $inbox = DirectMessages::getUsersInbox($userId);
        return $inbox;

    }

    //get user sentbox data
    public static function getUsersSentbox($userId)
    {
        $sentbox = DirectMessages::getUserSentbox($userId);
        return $sentbox;

    }

    //gets users threads that they have posted
    public static function getUsersThreads($userId)
    {
        $usersThreads = ThreadsModel::userProfileThreads($userId);
        return $usersThreads;

    }

    //gets a users single message
    public static function getMsg($msgId)
    {
        $userId = ModelFacade::getLoggedInUser()->id;
        $msg = DirectMessages::getDirectMessage($msgId);
        if (($msg->receiver != $userId) && ($msg->sender != $userId))
            header("Location: /DirectMsgInbox.php");
        return $msg;

    }

    //Count the amount of unread messages a user has
    public static function countUnreadMsgs()
    {
        $userId = ModelFacade::getLoggedInUser()->id;
        $messageCount = DirectMessages::getUsersUnreadCount($userId);
        return $messageCount;

    }

    //create a new message
    public static function createMsg($receiver, $subject, $message)
    {
        $sender = ModelFacade::getLoggedInUser()->id;
        $isCreated = DirectMessages::createMsg($receiver, $sender, $subject, $message);
        return $isCreated;

    }

    //delete a users message
    public static function deleteMsg($msgId)
    {
        $userId = ModelFacade::getLoggedInUser()->id;
        $isDeleted = DirectMessages::deleteMsg($msgId, $userId);
        return $isDeleted;

    }

    //mark read message as unread
    public static function markMsgRead($msgId)
    {
        DirectMessages::markAsRead($msgId);
    }

    //get a single thread
    public static function getThread($id)
    {
        $Threads = new ThreadsModel();
        $Threads = $Threads->getThread($id);
        return $Threads;
    }

    //get a threads comments
    public static function getThreadComments($threadId)
    {

        return (new ThreadsModel())->getThreadComments($threadId);


    }


    //Get all registered users from the database
    public static function GetAllUsers()
    {
        $users = new Users();
        return $users->GetAllUsers();
    }

    // Get a user from the database by Id
    public static function GetUserById($id)
    {
        $users = new Users();
        $user = $users->GetUserById($id);
        return $user;
    }

    // Get a user from the database by Name
    public static function getUserByName($name)
    {
        $users = new Users();
        $user = $users->getUserByName($name);
        return $user;
    }

    //Create a new Thread
    public static function insertThread($title, $content, $subcategory, $user)
    {
        $thread = new ThreadModel($title, $content, $subcategory, $user);
        return $thread->addThread();
    }

    //delete a users account
    public static function DeleteUser($id)
    {
        $usersModel = new Users();
        $threadsModel = new ThreadsModel();
        DirectMessages::removeUserMsgs($id);
        $threadsModel->deleteAllUsersComments($id);
        $threadsModel->deleteAllUsersThreads($id);
        return $usersModel->DeleteUser($id);
    }

    //add a comment to a thread
    public static function addComment($threadId, $comment, $userId)
    {

        $thread = new ThreadsModel();
        $thread->addComment($threadId, $comment, $userId);
        $thread->updateLastActivity($threadId);
    }

    //delete a comment with an admin account
    public static function AdminDeleteComment($id)
    {
        $threads = new ThreadsModel();
        return $threads->AdminDeleteComment($id);
    }

    //allows an admin to close a thread so new comments can not be added
    public static function closeThread($threadId, $closingMessage, $adminId)
    {
        $threads = new ThreadsModel();
        $threads->AdminCloseThread($threadId, $closingMessage, $adminId);

    }

    //checks if a thread has been closed
    public static function checkThreadClosed($threadId)
    {
        $thread = new ThreadsModel();
        return $thread->checkThreadClosed($threadId);

    }

    //check is subcategory has been disabled
    public static function checkSubcategoryDisabled($subcategoryId)
    {
        $categories = new Categories();
        return $categories->GetIsSubCategoryDisabled($subcategoryId);
    }

    //turns a date into a more readable format
    public static function friendlyDate($date)
    {
        if ($date == null) {
            return "";
        }
        $date = date_create($date);
        return date_format($date, 'l jS \of F Y h:i A');
    }

    //update a user account details
    public static function UpdateUser($user)
    {
        $users = new Users();
        return $users->UpdateUser($user);
    }

    //Allows an admin to add a new Category
    public static function AdminAddBoard($categoryName)
    {
        $categories = new Categories();
        return $categories->AddCategory($categoryName);
    }

    //allows an admin to edit a Category
    public static function AdminEditBoard($id, $categoryName)
    {
        $categories = new Categories();
        return $categories->EditCategory($id, $categoryName);
    }

    //allows a admin to add a subcategory
    public static function AdminAddSubcategory($categoryId, $subcategoryName)
    {
        $categories = new Categories();
        return $categories->AddSubcategory($categoryId, $subcategoryName);
    }

    //allows a admin to edit a subcategory
    public static function AdminEditSubcategory($id, $subcategoryName)
    {
        $categories = new Categories();
        return $categories->EditSubcategory($id, $subcategoryName);
    }


}


?>
<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/6/2015
 * Time: 10:28 PM
 */


require "/Model/ModelFacade.php";
//redirect if user not logged in
//ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        threadGet();
    } else {
        threadPost();
    }


}


function threadGet()
{
    //gets Post
    $thread = ModelFacade::getThread($_GET["id"]);

    if ($thread == null) {
        $message = "Sorry a thread with that id does not exist";
        include_once('/Views/ErrorPage.html');
    } else {
        //Check if subcategory or parent category is disabled
        if (ModelFacade::checkSubcategoryDisabled($thread->subCatId)) {
            $message = "Sorry, but this thread is not enabled for viewing";
            include_once('/Views/ErrorPage.html');
        } else {
            //get Post Comments
            $comments = ModelFacade::getThreadComments($_GET["id"]);
            include_once('/Views/Thread.html');
        }
    }
}

function threadPost()
{

    ModelFacade::kickIfBannedOrDeleted();

    $thread = ModelFacade::getThread($_GET["id"]);
    //get Post Comments

    //check if comment has text
    $emptyComment = false;
    if (trim($_POST["newComment"]) == "") {
        $emptyComment = true;
    } elseif (ModelFacade::checkThreadClosed($_GET["id"])) {

        header("location:Thread.php?id=" . $_GET["id"]);
    } else {
        //add comment
        ModelFacade::addComment($_GET["id"], htmlspecialchars($_POST["newComment"]), ModelFacade::getLoggedInUser()->id);

    }

    unset($_POST);
    $comments = ModelFacade::getThreadComments($_GET["id"]);
    //include_once('/Views/Thread.html');
    header("location:Thread.php?id=" . $_GET["id"]);
}


?>
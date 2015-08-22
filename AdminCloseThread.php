<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/12/2015
 * Time: 1:22 AM
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();


OnRequest();

function OnRequest()
{


    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        threadCloseGet();
    } else {


        //close Thread
        ModelFacade::closeThread($_GET["id"], $_POST["CloseReason"], ModelFacade::getLoggedInUser()->id);

        threadClosePost();

        header("location:Thread.php?id=" . $_GET["id"]);
    }


}


function threadCloseGet()
{
    //gets Post
    $thread = ModelFacade::getThread($_GET["id"]);
    //get Comment Count
    if ($thread == null) {
        $message = "Sorry but a thread with that id does not exist";
        include_once('/Views/ErrorPage.html');
    } else {
        include_once('/Views/AdminCloseThread.html');
    }
}

function threadClosePost()
{
    //gets Post
    $thread = ModelFacade::getThread($_GET["id"]);
    //get Comment Count


    include_once('/Views/AdminCloseThread.html');

}


?>
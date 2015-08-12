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

function OnRequest() {


    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        threadCloseGet();
    }
    else {

        //TODO: Check comment has data



        //close Thread
        ModelFacade::closeThread($_GET["id"], $_POST["CloseReason"], ModelFacade::getLoggedInUser()->id);

        threadClosePost();
    }



}


function threadCloseGet() {
    //gets Post
    $post = ModelFacade::getPost($_GET["id"]);
    //get Comment Count


    include_once('/Views/AdminCloseThread.html');

}

function threadClosePost() {
    //gets Post
    $post = ModelFacade::getPost($_GET["id"]);
    //get Comment Count


    include_once('/Views/AdminCloseThread.html');

}


?>
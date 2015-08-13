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
        //loginPost("", "");
        threadClosePost();
    }



}


function threadCloseGet() {
    //gets Post
    $thread = ModelFacade::getThread($_GET["id"]);
    //get Comment Count


    include_once('/Views/AdminCloseThread.html');

}

function threadClosePost() {
    //gets Post
    $thread = ModelFacade::getThread($_GET["id"]);
    //get Comment Count


    include_once('/Views/AdminCloseThread.html');

}


?>
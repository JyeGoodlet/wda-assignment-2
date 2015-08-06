<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/6/2015
 * Time: 10:32 PM
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();


OnRequest();

function OnRequest() {

    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        newThreadGet();
    }
    else {
        //loginPost("", "");
        newThreadPost();
    }
    $message = "test error";



}

function newThreadGet() {
    include_once('/Views/NewThread.html');

}

function newThreadPost() {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);



    if ( empty($title) or empty($content) ) {
        checkEmptyValues($title, $content);
    }
    else {
        echo "TODO post thread to mysql and then redirect";
    }

}

function checkEmptyValues($title, $content) {
    if (empty($title) ) {
        $titleReq = "has-warning has-feedback";
        $message = true;
    }
    if (empty($content)) {
        $contentReq = "has-warning has-feedback";
        $message = true;
    }
    include_once('/Views/NewThread.html');

}



?>
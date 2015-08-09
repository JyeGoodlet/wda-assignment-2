<?php
/**
 * -_-_- AUTHOR: jAsOnD -_-_-
 * modified from Jye Goodlet's code
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();


OnRequest();

function OnRequest() {



    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        newMessageGet();
    }
    else {
        //loginPost("", "");
        newMessagePost();
    }
    $errorMessage = "test error";



}

function newMessageGet() {

    if (isset($_GET['replyTo']))
        $replyTo = $_GET['replyTo'];
    else
        $replyTo = "";

    if (isset($_GET['replySubject']))
        $replySubject = $_GET['replySubject'];
    else
        $replySubject = "";

    include_once('/Views/DirectMsgCreate.html');

}

function newMessagePost() {

    //TODO consider creating validator // secureInput controller/helper class
    $receiver = htmlspecialchars(trim($_POST["receiver"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));




    if ( empty($receiver) or empty($subject) or empty($message) ) {
        checkEmptyValues($receiver, $subject, $message);
    }
    else {
        if (ModelFacade::createMsg($receiver,$subject, $message))
            header("location:DirectMsgInbox.php");
    }

}

function checkEmptyValues($receiver, $subject, $message) {
    if (empty($receiver) ) {
        $receiverReq = "has-warning has-feedback";
        $errorMessage = true;
    }
    if (empty($subject)) {
        $subjectReq = "has-warning has-feedback";
        $errorMessage = true;
    }
    if (empty($message)) {
        $messageReq = "has-warning has-feedback";
        $errorMessage = true;
    }
    include_once('/Views/DirectMsgCreate.html');

}




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

    ModelFacade::kickIfBanned();


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

    if (isset($_GET['sendTo']))
        $sendTo = $_GET['sendTo'];
    else
        $sendTo = "";

    if (isset($_GET['replySubject']))
        $replySubject = $_GET['replySubject'];
    else
        $replySubject = "";

    if  (isset($_GET['forwardMsgId'])) {
        $getMessage = ModelFacade::getMsg($_GET['forwardMsgId']);
        $fwdSubject = 'FW:' . $getMessage->subject;
        $fwdMessage = $getMessage->message;
        unset($getMessage);
    }
    else {
        $fwdSubject = "";
        $fwdMessage = "";
    }

    $allUsers= ModelFacade::GetAllUsers();
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
            header("location:DirectMsgSent.php?newMsgSent=true");
        else
            header("location:DirectMsgSent.php?newMsgSent=false");
    }

}

function checkEmptyValues($receiver, $subject, $message) {

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




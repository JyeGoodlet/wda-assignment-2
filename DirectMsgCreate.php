<?php
/**
 * -_-_- AUTHOR: jAsOnD -_-_-
 * modified from Jye Goodlet's code
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();


OnRequest();


//function loads when page is requested
function OnRequest()
{

    ModelFacade::kickIfBannedOrDeleted();


    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        newMessageGet();
    } else {
        //loginPost("", "");
        newMessagePost();
    }
    $errorMessage = "test error";


}

//function displays form to create a message and prefills data
function newMessageGet()
{

    if (isset($_GET['sendTo']))
        $sendTo = $_GET['sendTo'];
    else
        $sendTo = "";

    if (isset($_GET['replySubject']))
        $replySubject = $_GET['replySubject'];
    else
        $replySubject = "";

    if (isset($_GET['forwardMsgId'])) {
        $getMessage = ModelFacade::getMsg($_GET['forwardMsgId']);
        $fwdSubject = 'FW:' . $getMessage->subject;
        $fwdMessage = $getMessage->message;
        unset($getMessage);
    } else {
        $fwdSubject = "";
        $fwdMessage = "";
    }

    $allUsers = ModelFacade::GetAllUsers();
    include_once('/Views/DirectMsgCreate.html');

}

//function handles submission of a create message form
function newMessagePost()
{

    // sanitises data input from form.
    $receiver = htmlspecialchars(trim($_POST["receiver"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    //if successful redirect to sentbox with confirmation message
    if (empty($receiver) or empty($subject) or empty($message)) {
        checkEmptyValues($receiver, $subject, $message);
    } else {
        if (ModelFacade::createMsg($receiver, $subject, $message))
            header("location:DirectMsgSent.php?newMsgSent=true");
        else
            header("location:DirectMsgSent.php?newMsgSent=false");
    }

}

//check for empty values and prompt for resubmission of form with an error message
function checkEmptyValues($receiver, $subject, $message)
{

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




<?php
/**  -_-_- AUTHOR: jAsOnD -_-_-
 * displays the logged in users Sent box - messages sent */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
    if (isset($_POST['delMsg'])) {
        foreach ($_POST['delMsg'] as $eachDelMsg)
            ModelFacade::deleteMsg($eachDelMsg);
        header('Location: /DirectMsgSent.php');
    }

    $user = ModelFacade::getLoggedInUser();

    $messages = ModelFacade::getUsersSentbox($user->id);

    //display confirmation if message was just sent
    if (isset($_GET['newMsgSent'])) {
        $newMsgSent = $_GET['newMsgSent'];
        if ($newMsgSent === 'true')
            $newMsgSent = "MESSAGE SENT SUCCESSFULLY";
        else
            $newMsgSent = "MESSAGE SEND FAILED - NO SUCH USER EXISTS";
    } else
        $newMsgSent = "";

    include_once('/Views/DirectMsgSent.html');
}



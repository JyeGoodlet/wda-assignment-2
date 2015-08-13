<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

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

    $messages = $user->getUsersSentbox();

    if (isset($_GET['newMsgSent'])) {
        $newMsgSent = $_GET['newMsgSent'];
        if ($newMsgSent === 'true')
            $newMsgSent = "MESSAGE SENT SUCCESSFULLY";
        else
            $newMsgSent = "MESSAGE SEND FAILED - NO SUCH USER EXISTS";
    }
    else
        $newMsgSent = "";

    include_once('/Views/DirectMsgSent.html');
}



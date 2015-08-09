<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
    $messages = ModelFacade::getMsgSent();

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



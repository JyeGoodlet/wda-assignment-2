<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{

    $messages = ModelFacade::getMsgInbox();
    if (isset($_POST['delMsg'])) {
        foreach ($_POST['delMsg'] as $eachDelMsg)
            ModelFacade::deleteMsg($eachDelMsg);
        header('Location: /DirectMsgInbox.php');
    }
    include_once('/Views/DirectMsgInbox.html');
}



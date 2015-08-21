<?php
/**  -_-_- AUTHOR: jAsOnD -_-_-
   Display the logged in users INBOX */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

//function loads on page request
function OnRequest()
{
    $user = ModelFacade::getLoggedInUser();
    $messages = DirectMessages::getUsersInbox($user->id);
    if (isset($_POST['delMsg'])) {
        foreach ($_POST['delMsg'] as $eachDelMsg)
            ModelFacade::deleteMsg($eachDelMsg);
        header('Location: /DirectMsgInbox.php');
    }
    include_once('/Views/DirectMsgInbox.html');
}



<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
    $messages = ModelFacade::getUsersMsgs();
    include_once('/Views/DirectMsgList.html');
}



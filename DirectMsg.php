<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
  $selectedMsg = ModelFacade::getMsg($_GET["msgId"]);
    if (!$selectedMsg->isRead)
    ModelFacade::markMsgRead($selectedMsg->id);
  include_once('/Views/DirectMsg.html');
}


?>
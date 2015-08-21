<?php
/**  -_-_- AUTHOR: jAsOnD -_-_-
 Controller for Displaying a Direct Message
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

// function loaded when page is requested
function OnRequest()
{
  $selectedMsg = ModelFacade::getMsg($_GET["msgId"]);
    if (!$selectedMsg->isRead)
    ModelFacade::markMsgRead($selectedMsg->id);
  include_once('/Views/DirectMsg.html');
}


?>
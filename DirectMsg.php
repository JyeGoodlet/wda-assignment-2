<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
  $selectedMsg = ModelFacade::displayUsersMsg($_GET["msgId"]);
  include_once('/Views/DirectMsg.html');
}


?>
<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
  $post = ModelFacade::getPost($_GET["id"]);
  include_once('/Views/DirectMsg.html');
}


?>
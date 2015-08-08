<?php
/**
 * Created by PhpStorm.
 * User: jasonD
 * Date: 8/8/2015
 * Time: 9:10 AM
 */


require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{
  $messages = ModelFacade::getDirectMessages($_GET["id"]);
  include_once('/Views/Thread.html');
}


?>
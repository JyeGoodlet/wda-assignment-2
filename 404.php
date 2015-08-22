<?php
/*
 * This controller is used to show a 404 page
 *
 */

require "Model/ModelFacade.php";
onRequest();

function onRequest()
{

    include_once("Views/404.html");
}


?>
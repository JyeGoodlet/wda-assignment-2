<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/6/2015
 * Time: 10:28 PM
 */


require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{


}


?>
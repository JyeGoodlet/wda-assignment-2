<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/12/2015
 * Time: 1:22 AM
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();




OnRequest();

function OnRequest() {

    include_once('/Views/AdminCloseThread.html');
}

?>
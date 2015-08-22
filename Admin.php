<?php
require "/Model/ModelFacade.php";
/*
 * This controller is used to show a admin control page for admins only
 *
 */


//redirect if user not logged in as admin
ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{

    ModelFacade::kickIfBannedOrDeleted();
    include_once('/Views/Admin/Index.html');

}

?>
<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest() {

    ModelFacade::kickIfBanned();
    include_once('/Views/Admin/Index.html');

}

?>
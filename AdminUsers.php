<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest()
{
    if (isset($_SESSION['deleteUser'])) {
        $message = $_SESSION['deleteUser'];
    }

    include_once('/Views/Admin/Users.html');

}

?>
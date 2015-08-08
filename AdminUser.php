<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories

OnRequest();

function OnRequest() {

    if(!isset($_GET['id'])) {
        header("Location: /AdminUsers.php");
    }

    $user = GetUserById($_GET['id']);
    
    include_once('/Views/Admin/User.html');

}

function GetUserById($id) {
    $user = ModelFacade::GetUserById($id);
    return $user;
}

?>
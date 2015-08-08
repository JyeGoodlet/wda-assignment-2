<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin
ModelFacade::redirectUnauthorisedNotAdmin();

//get all categories and subcategories
OnRequest();

function OnRequest() {
    //get all categories and subcategories
    $page = null;

    if(isset($_GET['page'])) {
        $page = '/Views/Admin/' . $_GET['page'] . '.html';

    }
    else {
        $page = '/Views/Admin/Boards.html';
    }

    include_once('/Views/Admin.html');

}

function GetUserById($id) {
    $user = ModelFacade::GetUserById($id);
    return $user;
}


?>
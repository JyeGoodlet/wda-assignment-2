<?php
require "/Model/ModelFacade.php";
//redirect if user not logged in as admin

ModelFacade::redirectUnauthorisedNotAdmin();
OnRequest();

function OnRequest()
{

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        if (isset($_GET['id'])) {
            AdminDeleteComment($_GET['id']);
        }
    }

}

function AdminDeleteComment($id)
{

    $result = ModelFacade::AdminDeleteComment($id);
    header("Location: " . $_SERVER['HTTP_REFERER']);

}

?>
<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 13/08/2015
 * Time: 17:54
 */


//Require Model
require "Model/ModelFacade.php";

OnRequest();

function OnRequest()
{
    $requestMethod = $_SERVER['REQUEST_METHOD'];


    if ($requestMethod == "GET")
        deleteConfirmGet();
    else
        deleteConfirmPost();

}

function DeleteConfirmGet()
{
    include_once("/Views/DeleteUser.html");
}

function deleteConfirmPost()
{

    $currentUserId = ModelFacade::getLoggedInUser()->id;
    if ((isset($_POST['deleteAccount'])) && ($_POST['deleteAccount'] == "confirm")) {
        ModelFacade::DeleteUser($currentUserId);
        ModelFacade::logout();
        header("location: /Signup.php?delAccount=success");
    } else
        header("location: /Index.php");

}


?>
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

function DeleteConfirmGet() {
    include_once("/Views/DeleteConfirm.html");
}

function deleteConfirmPost() {}




?>
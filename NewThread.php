<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/6/2015
 * Time: 10:32 PM
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();


OnRequest();

function OnRequest()
{

    ModelFacade::kickIfBannedOrDeleted();
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    if ($requestMethod == "GET") {
        newThreadGet();
    } else {
        //loginPost("", "");
        newThreadPost();
    }
    $message = "test error";


}

function newThreadGet()
{
    $subcategory = ModelFacade::getSubCategory($_GET["subcategory"]);
    if ($subcategory == null) {
        $message = "Sorry Subcategory with that id does not exist";
        include_once('/Views/ErrorPage.html');
    } else {
        include_once('/Views/NewThread.html');
    }
}

function newThreadPost()
{
    $subcategory = $_GET["subcategory"];
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);


    if (empty($title) or empty($content)) {
        checkEmptyValues($title, $content);
    } else {
        $postId = ModelFacade::insertThread($title, $content, $subcategory, ModelFacade::getLoggedInUser()->id);
        header("location:Thread.php?id=" . $postId);

    }

}

function checkEmptyValues($title, $content)
{
    if (empty($title)) {
        $titleReq = "has-warning has-feedback";
        $message = true;
    }
    if (empty($content)) {
        $contentReq = "has-warning has-feedback";
        $message = true;
    }
    include_once('/Views/NewThread.html');

}


?>
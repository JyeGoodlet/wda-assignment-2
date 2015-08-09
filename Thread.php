<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/6/2015
 * Time: 10:28 PM
 */


require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{

  $requestMethod = $_SERVER['REQUEST_METHOD'];
  if ($requestMethod == "GET") {
    threadGet();
  }
  else {
    //loginPost("", "");
    threadPost();
  }


}


function threadGet() {
  //gets Post
  $post = ModelFacade::getPost($_GET["id"]);
  //get Post Comments
  $comments = ModelFacade::getPostComments($_GET["id"]);
  include_once('/Views/Thread.html');

}

function threadPost(){

  $post = ModelFacade::getPost($_GET["id"]);
  //get Post Comments

  //check if comment has text
  $emptyComment = false;
  if (trim($_POST["newComment"]) == "") {
    $emptyComment = true;
  }
  else {
    //add comment
    ModelFacade::addComment($_GET["id"], $_POST["newComment"], ModelFacade::getLoggedInUser()->id);
  }



  $comments = ModelFacade::getPostComments($_GET["id"]);
  include_once('/Views/Thread.html');
}



?>
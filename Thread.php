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
  //gets Post
  $post = ModelFacade::getPost($_GET["id"]);
  //get Post Comments
  $comments = ModelFacade::getPostComments($_GET["id"]);
  include_once('/Views/Thread.html');
}


?>
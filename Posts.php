<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/5/2015
 * Time: 12:45 AM
 *
 * this page will show all posts for a subcategory
 *
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

//get all categories and subcategories
OnRequest();

function OnRequest() {
    //get subcategory

    $subcategory = ModelFacade::getSubCategory($_GET["id"]);

    include_once('/Views/Posts.html');
}




?>

<?php

require "/Model/ModelFacade.php";
//redirect if user not logged in
//ModelFacade::redirectUnauthorised();

//get all categories and subcategories
OnRequest();

function OnRequest()
{
    //get all categories and subcategories
    $categories = ModelFacade::getAllCategoriesWithSubcategories();
    include_once('/Views/Index.html');
}


?>
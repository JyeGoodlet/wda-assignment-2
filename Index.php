<?php
/**
 * Created by PhpStorm.
 * User: sinisterdeath
 * Date: 8/2/2015
 * Time: 5:46 PM
 */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorises();

//get all categories and subcategories
OnRequest();

function OnRequest() {
    include_once('/Views/index.html');
}


?>
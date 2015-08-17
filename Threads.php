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
//ModelFacade::redirectUnauthorised();

//get all categories and subcategories
OnRequest();

function OnRequest() {
    //get subcategory
    $subcategory = ModelFacade::getSubCategory($_GET["id"]);

    if ($subcategory == null) {
        $message =  "Sorry but a thread with that id does not exist";
        include_once('/Views/ErrorPage.html');
    }else {
        //Check if subcategory or parent category is disabled
        if(ModelFacade::checkSubcategoryDisabled($subcategory->id)) {          

            $message = "Sorry, but this thread is not enabled for viewing";
            include_once('/Views/ErrorPage.html');
        }
        else {
            //get Posts
            $threads = ModelFacade::getThreads($subcategory->id);
            include_once('/Views/Threads.html');
        }
    }

}

?>

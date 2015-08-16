<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{


    if (isset($_GET['id'])) {
        $currentUser = ModelFacade::getLoggedInUser();
        $userDetails = ModelFacade::getUserDetails($_GET['id']);
        if ($userDetails == null)
        {
            $message = "Sorry a user with that id does not exist";
            include_once('/Views/ErrorPage.html');
        }
        else {
                $userThreads = ModelFacade::GetUsersThreads($_GET['id']);
                include_once('/Views/UserProfile.html');
        }


    }
    else {
        $message = "Sorry no user id was set";
        include_once('/Views/ErrorPage.html');
    }

}



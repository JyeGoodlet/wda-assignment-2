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
        $userThreads = ModelFacade::GetUsersThreads($_GET['id']);
    }
    include_once('/Views/UserProfile.html');
}



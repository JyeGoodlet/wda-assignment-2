<?php
/**  -_-_- AUTHOR: jAsOnD -_-_- */

require "/Model/ModelFacade.php";
//redirect if user not logged in
ModelFacade::redirectUnauthorised();

OnRequest();

function OnRequest()
{


    if (isset($_Get['id']))
        $userDetails = ModelFacade::getUserDetails($_Get['id']);



    include_once('/Views/UserProfile.html');
}


